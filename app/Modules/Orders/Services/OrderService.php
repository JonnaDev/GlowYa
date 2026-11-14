<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Orders\Jobs\SendOrderToShopifyJob;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use Illuminate\Database\UniqueConstraintViolationException;
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

        try {
            $order = $this->persist($payload, $source, $idempotencyKey, $items, $totalAmount);
        } catch (UniqueConstraintViolationException) {
            // Dos peticiones con la misma clave llegaron a la vez (doble clic):
            // la otra ganó la carrera y ya creó la orden y encoló su envío.
            return Order::where('idempotency_key', $idempotencyKey)->firstOrFail();
        }

        SendOrderToShopifyJob::dispatch($order)->afterCommit();

        return $order->fresh('items');
    }

    /**
     * Registra en la DB local un pedido que nació en Shopify fuera de este
     * backend (p. ej. creado por el bot de WhatsApp) y llegó por el webhook
     * orders/create.
     *
     * No encola `SendOrderToShopifyJob`: el pedido ya existe en Shopify y
     * reenviarlo lo duplicaría allí y en Dropi.
     *
     * Devuelve null cuando no hay nada que importar: el payload no trae id, o
     * el pedido lo creó este mismo backend (etiqueta `glowya`) y ya está en la DB.
     *
     * @param  array<string,mixed>  $shopifyOrder  Objeto order tal como lo envía Shopify.
     */
    public function importFromShopify(array $shopifyOrder): ?Order
    {
        $shopifyOrderId = (string) ($shopifyOrder['id'] ?? '');
        $tags = $this->parseTags($shopifyOrder['tags'] ?? '');

        if ($shopifyOrderId === '' || in_array('glowya', $tags, true)) {
            return null;
        }

        $existing = Order::where('shopify_order_id', $shopifyOrderId)->first();
        if ($existing !== null) {
            return $existing;
        }

        $address = $shopifyOrder['shipping_address']
            ?? $shopifyOrder['billing_address']
            ?? $shopifyOrder['customer']['default_address']
            ?? [];
        $customer = $shopifyOrder['customer'] ?? [];

        $fullName = trim((string) ($address['name']
            ?? trim(($address['first_name'] ?? $customer['first_name'] ?? '').' '.($address['last_name'] ?? $customer['last_name'] ?? ''))));

        $lineItems = $shopifyOrder['line_items'] ?? [];
        $itemsTotal = collect($lineItems)->sum(
            fn (array $li): float => (float) ($li['price'] ?? 0) * (int) ($li['quantity'] ?? 1)
        );

        // Shopify reintenta los webhooks: la clave derivada de su id hace que
        // una segunda entrega no cree un segundo registro.
        $idempotencyKey = "shopify:{$shopifyOrderId}";

        try {
            return DB::transaction(function () use ($shopifyOrder, $shopifyOrderId, $tags, $address, $customer, $fullName, $lineItems, $itemsTotal, $idempotencyKey): Order {
                $order = Order::create([
                    'idempotency_key' => $idempotencyKey,
                    'shopify_order_id' => $shopifyOrderId,
                    'source' => $this->resolveShopifySource($shopifyOrder, $tags),
                    'status_local' => 'sent_to_shopify',
                    'status_shopify' => $shopifyOrder['fulfillment_status'] ?? $shopifyOrder['financial_status'] ?? null,
                    'last_synced_at' => now(),
                    'recipient_full_name' => $fullName,
                    'recipient_id_number' => null,
                    'recipient_phone' => $this->normalizeColombianPhone(
                        $address['phone'] ?? $shopifyOrder['phone'] ?? $customer['phone'] ?? ''
                    ),
                    'recipient_email' => $shopifyOrder['email'] ?? $customer['email'] ?? null,
                    'recipient_department' => (string) ($address['province'] ?? ''),
                    'recipient_city' => (string) ($address['city'] ?? ''),
                    'recipient_neighborhood' => $address['address2'] ?? null,
                    'recipient_address_line' => (string) ($address['address1'] ?? ''),
                    'recipient_notes' => $shopifyOrder['note'] ?? null,
                    'total_amount' => (float) ($shopifyOrder['total_price'] ?? $itemsTotal),
                ]);

                foreach ($lineItems as $li) {
                    $variantId = (string) ($li['variant_id'] ?? '');
                    $product = $variantId !== ''
                        ? Product::where('shopify_variant_id', $variantId)->first()
                        : null;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product?->id,
                        'shopify_variant_id' => $variantId,
                        'title_snapshot' => (string) ($li['title'] ?? $li['name'] ?? $product?->title ?? 'Producto'),
                        'sku_snapshot' => ($li['sku'] ?? '') !== '' ? $li['sku'] : $product?->sku,
                        'quantity' => max(1, (int) ($li['quantity'] ?? 1)),
                        'unit_price' => (float) ($li['price'] ?? 0),
                    ]);
                }

                return $order->load('items');
            });
        } catch (UniqueConstraintViolationException) {
            // Dos entregas del mismo webhook llegaron a la vez.
            return Order::where('idempotency_key', $idempotencyKey)->firstOrFail();
        }
    }

    /**
     * `whatsapp_bot` si el pedido trae la marca configurada del bot (app_id de
     * su app de Shopify o etiqueta); `shopify` para cualquier otro pedido externo.
     *
     * @param  array<string,mixed>  $shopifyOrder
     * @param  array<int,string>  $tags
     */
    private function resolveShopifySource(array $shopifyOrder, array $tags): string
    {
        $botAppId = (string) config('services.shopify.bot_app_id', '');
        $botTag = strtolower(trim((string) config('services.shopify.bot_tag', '')));

        if ($botAppId !== '' && (string) ($shopifyOrder['app_id'] ?? '') === $botAppId) {
            return 'whatsapp_bot';
        }

        if ($botTag !== '' && in_array($botTag, $tags, true)) {
            return 'whatsapp_bot';
        }

        return 'shopify';
    }

    /**
     * @return array<int,string> etiquetas en minúsculas, sin espacios alrededor
     */
    private function parseTags(mixed $tags): array
    {
        if (! is_string($tags) || trim($tags) === '') {
            return [];
        }

        return array_values(array_filter(
            array_map(fn (string $t): string => strtolower(trim($t)), explode(',', $tags)),
            fn (string $t): bool => $t !== '',
        ));
    }

    /**
     * Deja el celular en 10 dígitos quitando separadores y el indicativo 57.
     * Si no encaja en ese formato se guarda tal cual llegó, sin separadores.
     */
    private function normalizeColombianPhone(mixed $value): string
    {
        $digits = preg_replace('/\D/', '', (string) $value) ?? '';

        if (strlen($digits) === 12 && str_starts_with($digits, '57')) {
            return substr($digits, 2);
        }

        return $digits;
    }

    /**
     * @param  array<int,array{product_id:int,shopify_variant_id:string,title:string,sku:?string,quantity:int,unit_price:float}>  $items
     */
    private function persist(array $payload, string $source, string $idempotencyKey, array $items, float $totalAmount): Order
    {
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

            return $order;
        });
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
