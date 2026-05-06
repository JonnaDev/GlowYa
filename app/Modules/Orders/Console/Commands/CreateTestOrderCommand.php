<?php

declare(strict_types=1);

namespace App\Modules\Orders\Console\Commands;

use App\Modules\Catalog\Models\Product;
use App\Modules\Orders\Services\OrderService;
use Illuminate\Console\Command;
use Throwable;

class CreateTestOrderCommand extends Command
{
    protected $signature = 'orders:test-create';

    protected $description = 'Create a test order using the first imported product (Glofit dev only)';

    public function handle(OrderService $orders): int
    {
        $product = Product::first();

        if ($product === null) {
            $this->error('No hay productos en DB local. Corré primero: php artisan shopify:import-products');

            return self::FAILURE;
        }

        $this->info("Usando producto: {$product->title} ({$product->shopify_product_id})");
        $this->newLine();

        $payload = [
            'recipient_full_name' => 'Cliente Prueba Glofit',
            'recipient_id_number' => '1234567890',
            'recipient_phone' => '3155771146',
            'recipient_email' => 'test+glofit@example.com',
            'recipient_department' => 'Huila',
            'recipient_city' => 'Neiva',
            'recipient_neighborhood' => 'Centro',
            'recipient_address_line' => 'Carrera 29 #51-119, Torre 7 ap 303',
            'recipient_notes' => 'Pedido de prueba — flujo Laravel → Shopify → Dropi',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ];

        try {
            $order = $orders->createFromPayload($payload, source: 'admin_panel');
        } catch (Throwable $e) {
            $this->error('Error: '.$e->getMessage());
            $this->line($e->getTraceAsString());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Orden creada exitosamente.');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Local Order ID', $order->id],
                ['Idempotency Key', $order->idempotency_key],
                ['Shopify Order ID', $order->shopify_order_id ?? '(pendiente)'],
                ['Status local', $order->status_local],
                ['Total', '$'.number_format((float) $order->total_amount, 0, ',', '.').' COP'],
                ['Items', (string) $order->items->count()],
            ],
        );

        $this->newLine();
        $this->line('▸ Verificá en Shopify admin → Orders');
        $this->line('▸ Verificá en Dropi → Mis órdenes (debería estar en PENDIENTE_CONFIRMACION)');

        return self::SUCCESS;
    }
}
