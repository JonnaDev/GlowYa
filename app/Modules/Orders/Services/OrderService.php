<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Shopify\Services\ShopifyClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    public function __construct(
        private readonly ShopifyClient $shopify,
    ) {}

    /**
     * Crea una orden local + la envía a Shopify (que a su vez la propaga a Dropi).
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

        return DB::transaction(function () use ($payload, $source, $idempotencyKey, $items, $totalAmount): Order {
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

            $this->dispatchToShopify($order, $items);

            return $order->fresh('items');
        });
    }

    /**
     * Resuelve los items contra la DB local + extrae los datos snapshot.
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

    /**
     * Envía la orden a Shopify y guarda el shopify_order_id local.
     *
     * NOTA MVP: hoy es síncrono dentro de la transacción para que el test sea
     * trivial. En producción esto se mueve a SendOrderToShopifyJob (cola)
     * para no bloquear el request del cliente.
     */
    private function dispatchToShopify(Order $order, array $items): void
    {
        $payload = $this->buildShopifyPayload($order, $items);

        $shopifyOrder = $this->shopify->createOrder($payload);

        $order->update([
            'shopify_order_id' => (string) $shopifyOrder['id'],
            'status_local' => 'sent_to_shopify',
            'last_synced_at' => now(),
        ]);
    }

    private function buildShopifyPayload(Order $order, array $items): array
    {
        [$firstName, $lastName] = $this->splitName($order->recipient_full_name);

        $lineItems = array_map(fn (array $i): array => [
            'variant_id' => (int) $i['shopify_variant_id'],
            'quantity' => (int) $i['quantity'],
        ], $items);

        return [
            'line_items' => $lineItems,
            'email' => $order->recipient_email,
            'phone' => $order->recipient_phone,
            'note' => sprintf(
                "Glofit Order #%d | Cédula: %s%s",
                $order->id,
                $order->recipient_id_number,
                $order->recipient_notes ? "\nNotas: {$order->recipient_notes}" : '',
            ),
            'tags' => "glofit,source:{$order->source}",
            'shipping_address' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address1' => $order->recipient_address_line,
                'address2' => $order->recipient_neighborhood,
                'city' => $order->recipient_city,
                'province' => $order->recipient_department,
                'country' => 'Colombia',
                'country_code' => 'CO',
                'phone' => $order->recipient_phone,
                'zip' => '000000',
            ],
            'billing_address' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address1' => $order->recipient_address_line,
                'address2' => $order->recipient_neighborhood,
                'city' => $order->recipient_city,
                'province' => $order->recipient_department,
                'country' => 'Colombia',
                'country_code' => 'CO',
                'phone' => $order->recipient_phone,
                'zip' => '000000',
            ],
            'send_receipt' => false,
            'send_fulfillment_receipt' => false,
        ];
    }

    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [
            $parts[0] ?? $fullName,
            $parts[1] ?? '',
        ];
    }
}
