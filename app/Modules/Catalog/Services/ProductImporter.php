<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Shopify\Services\ShopifyClient;

class ProductImporter
{
    public function __construct(
        private readonly ShopifyClient $shopify,
    ) {}

    /**
     * Import all products from Shopify into the local DB.
     *
     * @return array{created:int, updated:int, skipped:int}
     */
    public function importAll(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0];

        $shopifyProducts = $this->shopify->products(['limit' => 250]);

        foreach ($shopifyProducts as $payload) {
            $result = $this->upsertProduct($payload);
            ++$stats[$result];
        }

        return $stats;
    }

    private function upsertProduct(array $payload): string
    {
        $variant = $payload['variants'][0] ?? null;

        if ($variant === null) {
            return 'skipped';
        }

        $data = [
            'shopify_product_id' => (string) $payload['id'],
            'shopify_variant_id' => (string) $variant['id'],
            'title' => $payload['title'],
            'handle' => $payload['handle'],
            'body_html' => $payload['body_html'] ?? null,
            'vendor' => $payload['vendor'] ?? null,
            'product_type' => $payload['product_type'] ?? null,
            'tags' => $payload['tags'] ?? null,
            'status' => $payload['status'] ?? 'active',
            'sku' => $variant['sku'] ?? null,
            'price' => $variant['price'],
            'compare_at_price' => $variant['compare_at_price'] ?? null,
            'inventory_quantity' => (int) ($variant['inventory_quantity'] ?? 0),
            'weight' => $variant['weight'] ?? null,
            'image_url' => $payload['image']['src'] ?? null,
            'image_alt' => $payload['image']['alt'] ?? null,
            'shopify_updated_at' => $payload['updated_at'] ?? null,
            'imported_at' => now(),
        ];

        $data['content_hash'] = $this->computeHash($data);

        $existing = Product::withTrashed()
            ->where('shopify_product_id', $data['shopify_product_id'])
            ->first();

        if ($existing !== null) {
            if ($existing->content_hash === $data['content_hash'] && $existing->deleted_at === null) {
                return 'skipped';
            }

            $existing->fill($data);

            if ($existing->trashed()) {
                $existing->restore();
            }

            $existing->save();

            return 'updated';
        }

        Product::create($data);

        return 'created';
    }

    private function computeHash(array $data): string
    {
        $relevant = [
            'title' => $data['title'],
            'body_html' => $data['body_html'],
            'vendor' => $data['vendor'],
            'product_type' => $data['product_type'],
            'tags' => $data['tags'],
            'status' => $data['status'],
            'sku' => $data['sku'],
            'price' => $data['price'],
            'compare_at_price' => $data['compare_at_price'],
            'inventory_quantity' => $data['inventory_quantity'],
            'image_url' => $data['image_url'],
        ];

        return hash('sha256', (string) json_encode($relevant));
    }
}
