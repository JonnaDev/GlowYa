<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Orders\Jobs\SendOrderToShopifyJob;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    /**
     * Crea una orden local + encola el envío a Shopify.
     *
     * El cliente recibe respuesta inmediata con la orden persistida; el job
     * (`SendOrderToShopifyJob`) hace la llamada HTTP a Shopify Admin API en
     * background con reintentos. Shopify a su vez propaga a Dropi vía la
     * integración nativa de partners.
     *
     * Payload esperado:
     * - source: web_form|whatsapp_bot|admin_panel
     * - recipient_full_name, recipient_id_number, recipient_phone, recipient_email
     * - recipient_department, recipient_city, recipient_neighborhood (nullable), recipient_address_line
     * - recipient_notes (nullable)
     * - items: array of ['product_id' => int, 'quantity' => int]
     * - idempotency_key (nullable, se genera si falta)
     */
    public function createFromPayload(array $payload, string $source = 'admin_panel'): Order
    {
        $idempotencyKey = $payload['idempotency_key'] ?? (string) Str::uuid();

        $existing = Order::where('idempotency_key', $idempotencyKey)->first();
        if ($existing !== null) {
            return $existing;
        }

        $items = $this->resolveItems($payload['items'] ?? []);
        $totalAmount = collect($items)->sum(
            fn (array $i): float => (float) $i['unit_price'] * (int) $i['quantity']
        );

        $order = DB::transaction(function () use ($payload, $source, $idempotencyKey, $items, $totalAmount): Order {
            $order = Order::create([
                'idempotency_key' => $idempotencyKey,
                'source' => $source,
                'status_local' => 'pending_confirmation',
                'recipient_full_name' => $payload['recipient_full_name'],
                'recipient_id_number' => $payload['recipient_id_number'],
                'recipient_phone' => $payload['recipient_phone'],
                'recipient_email' => $payload['recipient_email'],
                'recipient_department' => $payload['recipient_department'],
                'recipient_city' => $payload['recipient_city'],
                'recipient_neighborhood' => $payload['recipient_neighborhood'] ?? null,
                'recipient_address_line' => $payload['recipient_address_line'],
                'recipient_notes' => $payload['recipient_notes'] ?? null,
                'total_amount' => $totalAmount,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'shopify_variant_id' => $item['shopify_variant_id'],
                    'title_snapshot' => $item['title'],
                    'sku_snapshot' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            return $order;
        });

        SendOrderToShopifyJob::dispatch($order)->afterCommit();

        return $order->fresh('items');
    }

    /**
     * Resuelve los items contra la DB local + extrae los datos snapshot.
     *
     * @return array<int,array{product_id:int,shopify_variant_id:string,title:string,sku:?string,quantity:int,unit_price:float}>
     */
    private function resolveItems(array $rawItems): array
    {
        if ($rawItems === []) {
            throw new RuntimeException('Order must have at least one item.');
        }

        $resolved = [];

        foreach ($rawItems as $raw) {
            $product = Product::findOrFail($raw['product_id']);

            $resolved[] = [
                'product_id' => $product->id,
                'shopify_variant_id' => $product->shopify_variant_id,
                'title' => $product->title,
                'sku' => $product->sku,
                'quantity' => max(1, (int) $raw['quantity']),
                'unit_price' => (float) $product->price,
            ];
        }

        return $resolved;
    }
}
