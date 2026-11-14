<?php

namespace Tests\Feature\Shopify;

use App\Modules\Catalog\Models\Product;
use App\Modules\Orders\Jobs\SendOrderToShopifyJob;
use App\Modules\Orders\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShopifyOrderCreatedWebhookTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'test-webhook-secret';

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        config([
            'services.shopify.webhook_secret' => self::SECRET,
            'services.shopify.bot_app_id' => null,
            'services.shopify.bot_tag' => 'whatsapp',
        ]);

        $this->product = Product::create([
            'shopify_product_id' => '111',
            'shopify_variant_id' => '222',
            'title' => 'Producto de prueba',
            'handle' => 'producto-de-prueba',
            'sku' => 'SKU-1',
            'price' => 70000,
        ]);
    }

    /** Envía un webhook firmado igual que Shopify (HMAC-SHA256 del cuerpo, en base64). */
    private function webhook(string $topic, array $payload, ?string $signature = null): TestResponse
    {
        $body = json_encode($payload);
        $signature ??= base64_encode(hash_hmac('sha256', $body, self::SECRET, true));

        return $this->call('POST', '/api/webhooks/shopify', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_SHOPIFY_TOPIC' => $topic,
            'HTTP_X_SHOPIFY_HMAC_SHA256' => $signature,
        ], $body);
    }

    /** @return array<string,mixed> */
    private function shopifyOrder(array $overrides = []): array
    {
        return array_merge([
            'id' => 5550001112223,
            'email' => 'cliente@example.com',
            'phone' => '+573155771146',
            'note' => 'Entregar en portería',
            'tags' => '',
            'source_name' => '1234567',
            'app_id' => 1234567,
            'financial_status' => 'pending',
            'fulfillment_status' => null,
            'total_price' => '140000.00',
            'line_items' => [
                [
                    'variant_id' => 222,
                    'title' => 'Producto de prueba',
                    'sku' => 'SKU-1',
                    'quantity' => 2,
                    'price' => '70000.00',
                ],
            ],
            'shipping_address' => [
                'name' => 'Catalina Rodríguez',
                'first_name' => 'Catalina',
                'last_name' => 'Rodríguez',
                'address1' => 'Carrera 29 # 51-119',
                'address2' => 'Centro',
                'city' => 'Neiva',
                'province' => 'Huila',
                'phone' => '+57 315 577 1146',
            ],
        ], $overrides);
    }

    public function test_external_order_is_imported_without_resending_to_shopify(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder())
            ->assertOk()
            ->assertJson(['status' => 'imported']);

        $order = Order::with('items')->sole();

        $this->assertSame('5550001112223', $order->shopify_order_id);
        $this->assertSame('shopify:5550001112223', $order->idempotency_key);
        $this->assertSame('shopify', $order->source);
        $this->assertSame('sent_to_shopify', $order->status_local);
        $this->assertSame('pending', $order->status_shopify);
        $this->assertSame('Catalina Rodríguez', $order->recipient_full_name);
        $this->assertSame('3155771146', $order->recipient_phone);
        $this->assertSame('cliente@example.com', $order->recipient_email);
        $this->assertNull($order->recipient_id_number);
        $this->assertSame('Huila', $order->recipient_department);
        $this->assertSame('Neiva', $order->recipient_city);
        $this->assertSame('Centro', $order->recipient_neighborhood);
        $this->assertSame('Carrera 29 # 51-119', $order->recipient_address_line);
        $this->assertSame('140000.00', $order->total_amount);

        $item = $order->items->sole();
        $this->assertSame($this->product->id, $item->product_id);
        $this->assertSame('222', $item->shopify_variant_id);
        $this->assertSame(2, $item->quantity);
        $this->assertSame('70000.00', $item->unit_price);

        Queue::assertNotPushed(SendOrderToShopifyJob::class);
    }

    public function test_order_created_by_this_backend_is_ignored(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder(['tags' => 'glowya, source:web_form']))
            ->assertOk()
            ->assertJson(['status' => 'ignored']);

        $this->assertSame(0, Order::count());
    }

    public function test_repeated_delivery_does_not_duplicate_the_order(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder())->assertJson(['status' => 'imported']);
        $this->webhook('orders/create', $this->shopifyOrder())->assertJson(['status' => 'already_exists']);

        $this->assertSame(1, Order::count());
    }

    public function test_order_tagged_by_the_bot_gets_whatsapp_source(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder(['tags' => 'COD, WhatsApp']))->assertOk();

        $this->assertSame('whatsapp_bot', Order::sole()->source);
    }

    public function test_order_from_the_configured_bot_app_gets_whatsapp_source(): void
    {
        config(['services.shopify.bot_app_id' => '1234567']);

        $this->webhook('orders/create', $this->shopifyOrder())->assertOk();

        $this->assertSame('whatsapp_bot', Order::sole()->source);
    }

    public function test_order_with_missing_optional_data_is_still_imported(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder([
            'email' => null,
            'phone' => null,
            'note' => null,
            'shipping_address' => null,
            'line_items' => [
                ['variant_id' => 999, 'title' => 'Producto no importado', 'quantity' => 1, 'price' => '50000.00'],
            ],
        ]))->assertOk()->assertJson(['status' => 'imported']);

        $order = Order::with('items')->sole();

        $this->assertNull($order->recipient_email);
        $this->assertSame('', $order->recipient_phone);
        $this->assertNull($order->items->sole()->product_id);
        $this->assertSame('Producto no importado', $order->items->sole()->title_snapshot);
    }

    public function test_imported_order_receives_tracking_from_later_webhooks(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder())->assertOk();

        $this->webhook('fulfillments/create', [
            'id' => 777,
            'order_id' => 5550001112223,
            'tracking_number' => 'GUIA-123',
            'tracking_url' => 'https://example.com/guia/123',
        ])->assertOk()->assertJson(['status' => 'fulfilled']);

        $order = Order::sole();
        $this->assertSame('fulfilled', $order->status_local);
        $this->assertSame('GUIA-123', $order->tracking_number);
    }

    public function test_webhook_with_invalid_signature_is_rejected(): void
    {
        $this->webhook('orders/create', $this->shopifyOrder(), signature: 'firma-falsa')
            ->assertUnauthorized();

        $this->assertSame(0, Order::count());
    }
}
