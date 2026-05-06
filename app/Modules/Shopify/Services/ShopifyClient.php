<?php

declare(strict_types=1);

namespace App\Modules\Shopify\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ShopifyClient
{
    public function __construct(
        private readonly string $store,
        private readonly string $apiVersion,
        private readonly string $accessToken,
    ) {}

    public function shop(): array
    {
        return $this->get('shop')['shop'];
    }

    public function products(array $params = []): array
    {
        return $this->get('products', $params)['products'];
    }

    public function product(string|int $id): array
    {
        return $this->get("products/{$id}")['product'];
    }

    public function createOrder(array $payload): array
    {
        return $this->post('orders', ['order' => $payload])['order'];
    }

    public function get(string $resource, array $query = []): array
    {
        return $this->request()->get($this->url($resource), $query)->throw()->json();
    }

    public function post(string $resource, array $body): array
    {
        return $this->request()->post($this->url($resource), $body)->throw()->json();
    }

    public function put(string $resource, array $body): array
    {
        return $this->request()->put($this->url($resource), $body)->throw()->json();
    }

    public function delete(string $resource): Response
    {
        return $this->request()->delete($this->url($resource))->throw();
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout(15);
    }

    private function url(string $resource): string
    {
        return "https://{$this->store}/admin/api/{$this->apiVersion}/{$resource}.json";
    }
}
