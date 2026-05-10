<?php

declare(strict_types=1);

namespace App\Modules\Orders\Jobs;

use App\Modules\Orders\Models\Order;
use App\Modules\Shopify\Services\ShopifyClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendOrderToShopifyJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    /** @var array<int,int> backoff seconds (10s, 30s, 1m, 5m, 15m) */
    public array $backoff = [10, 30, 60, 300, 900];

    public function __construct(
        public Order $order,
    ) {}

    public function handle(ShopifyClient $shopify): void
    {
        $this->order->refresh()->loadMissing('items');

        if ($this->order->shopify_order_id !== null) {
            return;
        }

        $items = $this->order->items->map(fn ($item): array => [
            'shopify_variant_id' => $item->shopify_variant_id,
            'quantity' => (int) $item->quantity,
        ])->all();

        $shopifyOrder = $shopify->createOrder($this->buildPayload($items));

        $this->order->update([
            'shopify_order_id' => (string) $shopifyOrder['id'],
            'status_local' => 'sent_to_shopify',
            'last_synced_at' => now(),
        ]);
    }

    public function failed(?Throwable $e): void
    {
        report($e);
    }

    /**
     * @param  array<int,array{shopify_variant_id:string,quantity:int}>  $items
     * @return array<string,mixed>
     */
    private function buildPayload(array $items): array
    {
        $order = $this->order;
        [$firstName, $lastName] = $this->splitName($order->recipient_full_name);

        $lineItems = array_map(fn (array $i): array => [
            'variant_id' => (int) $i['shopify_variant_id'],
            'quantity' => (int) $i['quantity'],
        ], $items);

        $address = [
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
        ];

        return [
            'line_items' => $lineItems,
            'email' => $order->recipient_email,
            'phone' => $order->recipient_phone,
            'note' => sprintf(
                "GlowYa Order #%d | Cédula: %s%s",
                $order->id,
                $order->recipient_id_number,
                $order->recipient_notes ? "\nNotas: {$order->recipient_notes}" : '',
            ),
            'tags' => "glowya,source:{$order->source}",
            'shipping_address' => $address,
            'billing_address' => $address,
            'send_receipt' => false,
            'send_fulfillment_receipt' => false,
        ];
    }

    /**
     * @return array{0:string,1:string}
     */
    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [
            $parts[0] ?? $fullName,
            $parts[1] ?? '',
        ];
    }
}
