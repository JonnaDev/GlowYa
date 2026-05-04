<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ProductImporter;
use App\Modules\Shopify\Services\ShopifyClient;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ImportController extends Controller
{
    public function __invoke(ShopifyClient $shopify): Response
    {
        $shopifyError = null;
        $shopifyProducts = [];

        try {
            $shopifyProducts = collect($shopify->products(['limit' => 250]))
                ->map(function (array $payload): array {
                    $variant = $payload['variants'][0] ?? [];

                    return [
                        'shopify_product_id' => (string) $payload['id'],
                        'title' => $payload['title'],
                        'handle' => $payload['handle'] ?? null,
                        'status' => $payload['status'] ?? 'unknown',
                        'price' => $variant['price'] ?? null,
                        'sku' => $variant['sku'] ?? null,
                        'inventory_quantity' => (int) ($variant['inventory_quantity'] ?? 0),
                        'image_url' => $payload['image']['src'] ?? null,
                        'updated_at' => $payload['updated_at'] ?? null,
                    ];
                })
                ->all();
        } catch (Throwable $e) {
            $shopifyError = $e->getMessage();
        }

        $local = Product::query()
            ->orderByDesc('imported_at')
            ->get([
                'id',
                'shopify_product_id',
                'title',
                'handle',
                'status',
                'price',
                'sku',
                'inventory_quantity',
                'image_url',
                'shopify_updated_at',
                'imported_at',
                'content_hash',
            ])
            ->map(fn (Product $p): array => [
                'id' => $p->id,
                'shopify_product_id' => $p->shopify_product_id,
                'title' => $p->title,
                'handle' => $p->handle,
                'status' => $p->status,
                'price' => $p->price,
                'sku' => $p->sku,
                'inventory_quantity' => $p->inventory_quantity,
                'image_url' => $p->image_url,
                'shopify_updated_at' => $p->shopify_updated_at?->toIso8601String(),
                'imported_at' => $p->imported_at?->toIso8601String(),
                'content_hash' => $p->content_hash,
            ])
            ->all();

        return Inertia::render('Catalog/Import', [
            'shopifyProducts' => $shopifyProducts,
            'localProducts' => $local,
            'shopifyError' => $shopifyError,
        ]);
    }

    public function sync(ProductImporter $importer): RedirectResponse
    {
        try {
            $stats = $importer->importAll();
        } catch (Throwable $e) {
            return back()->with('error', 'Error al sincronizar: '.$e->getMessage());
        }

        $message = sprintf(
            'Sincronizado. %d nuevos · %d actualizados · %d sin cambios.',
            $stats['created'],
            $stats['updated'],
            $stats['skipped'],
        );

        return back()->with('success', $message);
    }
}
