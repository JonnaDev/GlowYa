<?php

namespace Tests\Feature\Orders;

use App\Modules\Catalog\Models\Product;
use App\Modules\Landing\Models\Landing;
use App\Modules\Orders\Jobs\SendOrderToShopifyJob;
use App\Modules\Orders\Models\Order;
use App\Modules\Shopify\Services\ShopifyClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $product = Product::create([
            'shopify_product_id' => '111',
            'shopify_variant_id' => '222',
            'title' => 'Producto de prueba',
            'handle' => 'producto-de-prueba',
            'price' => 70000,
        ]);

        Landing::create([
            'slug' => 'demo',
            'title' => 'Landing demo',
            'blade_view' => 'landings.demo',
            'product_id' => $product->id,
            'is_active' => true,
        ]);
    }

    /** @return array<string,mixed> */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'Catalina Rodríguez',
            'id_number' => '1029880299',
            'phone' => '3155771146',
            'email' => 'catalina@example.com',
            'department' => 'Huila',
            'city' => 'Neiva',
            'neighborhood' => 'Centro',
            'address' => 'Carrera 29 # 51-119',
            'quantity' => 1,
            'notes' => null,
        ], $overrides);
    }

    public function test_valid_order_is_created_and_queued_for_shopify(): void
    {
        $this->post('/demo/order', $this->validPayload())
            ->assertRedirect('/demo')
            ->assertSessionHasNoErrors();

        $this->assertSame(1, Order::count());
        Queue::assertPushed(SendOrderToShopifyJob::class, 1);
    }

    public function test_phone_and_id_number_are_normalized_before_saving(): void
    {
        $this->post('/demo/order', $this->validPayload([
            'phone' => '+57 315 577-1146',
            'id_number' => '1.029.880.299',
        ]))->assertSessionHasNoErrors();

        $order = Order::firstOrFail();
        $this->assertSame('3155771146', $order->recipient_phone);
        $this->assertSame('1029880299', $order->recipient_id_number);
    }

    /**
     * Datos reales del pedido #5 de producción (bot de julio 2026).
     */
    public function test_bot_submission_seen_in_production_is_rejected(): void
    {
        $this->post('/demo/order', [
            'full_name' => 'xojfxfdjmw',
            'id_number' => 'rhjyjkkrni',
            'phone' => '+1-836-048-2802',
            'email' => 'exisrqpt@immenseignite.info',
            'department' => 'Selecciona…',
            'city' => 'uydfljqdzo',
            'neighborhood' => 'tylspxlxml',
            'address' => 'pkvlnreiyh',
            'quantity' => 1,
            'notes' => 'pjgkzfxqjgsxrdkmvehttfehqoefdj',
        ])->assertSessionHasErrors(['id_number', 'phone', 'department']);

        $this->assertSame(0, Order::count());
        Queue::assertNothingPushed();
    }

    #[DataProvider('invalidFields')]
    public function test_invalid_field_is_rejected(string $field, mixed $value): void
    {
        $this->post('/demo/order', $this->validPayload([$field => $value]))
            ->assertSessionHasErrors([$field]);

        $this->assertSame(0, Order::count());
        Queue::assertNothingPushed();
    }

    /** @return array<string,array{0:string,1:mixed}> */
    public static function invalidFields(): array
    {
        return [
            'departamento fuera de la lista' => ['department', 'Texas'],
            'departamento placeholder' => ['department', 'Selecciona…'],
            'celular extranjero' => ['phone', '+1-836-048-2802'],
            'celular de 9 dígitos' => ['phone', '323130899'],
            'celular que no empieza por 3' => ['phone', '6015551234'],
            'cédula con letras' => ['id_number', 'rhjyjkkrni'],
            'cédula muy corta' => ['id_number', '12345'],
            'nombre con números' => ['full_name', 'Cliente 123'],
            'nombre con url' => ['full_name', 'http://spam.example'],
            'cantidad excesiva' => ['quantity', 6],
        ];
    }

    public function test_honeypot_submission_is_silently_dropped(): void
    {
        $this->post('/demo/order', $this->validPayload(['extra_reference' => 'soy un bot']))
            ->assertRedirect('/demo')
            ->assertSessionHas('success');

        $this->assertSame(0, Order::count());
        Queue::assertNothingPushed();
    }

    public function test_cancelled_order_is_not_sent_to_shopify(): void
    {
        Http::fake();

        $order = Order::create([
            'idempotency_key' => 'test-cancelled',
            'source' => 'web_form',
            'status_local' => 'cancelled',
            'cancellation_reason' => 'spam',
            'recipient_full_name' => 'xojfxfdjmw',
            'recipient_id_number' => 'rhjyjkkrni',
            'recipient_phone' => '+1-836-048-2802',
            'recipient_email' => 'bot@example.com',
            'recipient_department' => 'Selecciona…',
            'recipient_city' => 'uydfljqdzo',
            'recipient_address_line' => 'pkvlnreiyh',
            'total_amount' => 70000,
        ]);

        (new SendOrderToShopifyJob($order))->handle(app(ShopifyClient::class));

        Http::assertNothingSent();
        $this->assertNull($order->fresh()->shopify_order_id);
    }
}
