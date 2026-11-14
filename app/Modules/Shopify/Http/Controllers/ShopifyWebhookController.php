<?php

declare(strict_types=1);

namespace App\Modules\Shopify\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Recibe webhooks de Shopify y actualiza el estado de las órdenes en la DB local.
 *
 * Flujo: Dropi cumple la orden → Shopify recibe el fulfillment →
 *        Shopify dispara webhook → Laravel actualiza status_local + status_shopify.
 *
 * Topics manejados:
 *  - orders/create      → registra pedidos creados fuera de este backend
 *  - orders/fulfilled  → status_local = fulfilled
 *  - orders/cancelled   → status_local = cancelled
 *  - orders/updated     → sincroniza status_shopify (campo genérico)
 */
class ShopifyWebhookController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
    ) {}

    /**
     * POST /webhooks/shopify
     *
     * El topic viene en el header X-Shopify-Topic.
     */
    public function handle(Request $request): JsonResponse
    {
        $topic = $request->header('X-Shopify-Topic', '');
        $payload = $request->all();

        Log::channel('single')->info('[Shopify Webhook]', [
            'topic' => $topic,
            'shopify_order_id' => $payload['id'] ?? null,
        ]);

        return match ($topic) {
            'orders/create' => $this->handleOrderCreated($payload),
            'orders/fulfilled' => $this->handleFulfilled($payload),
            'fulfillments/create' => $this->handleFulfillmentCreated($payload),
            'fulfillments/update' => $this->handleFulfillmentUpdated($payload),
            'orders/cancelled' => $this->handleCancelled($payload),
            'orders/updated' => $this->handleUpdated($payload),
            default => response()->json(['ignored' => true], 200),
        };
    }

    /**
     * orders/create — Pedido nuevo en Shopify. Los que creó este backend ya
     * están en la DB y se ignoran; los externos (p. ej. el bot de WhatsApp)
     * se registran localmente para que aparezcan en el panel y reciban los
     * siguientes webhooks (guía, cancelación).
     */
    private function handleOrderCreated(array $payload): JsonResponse
    {
        $order = $this->orders->importFromShopify($payload);

        if ($order === null) {
            return response()->json(['status' => 'ignored'], 200);
        }

        if (! $order->wasRecentlyCreated) {
            return response()->json(['status' => 'already_exists', 'order_id' => $order->id]);
        }

        // source_name y app_id quedan en el log para poder afinar cómo se
        // identifica el origen (ver services.shopify.bot_app_id / bot_tag).
        Log::channel('single')->info('[Webhook] External order imported', [
            'order_id' => $order->id,
            'shopify_order_id' => $order->shopify_order_id,
            'source' => $order->source,
            'shopify_source_name' => $payload['source_name'] ?? null,
            'shopify_app_id' => $payload['app_id'] ?? null,
            'shopify_tags' => $payload['tags'] ?? null,
        ]);

        return response()->json(['status' => 'imported', 'order_id' => $order->id]);
    }

    /**
     * fulfillments/create — Dropi cumple la orden → Shopify crea un fulfillment.
     * El payload es un objeto fulfillment (no un order), con order_id adentro.
     */
    private function handleFulfillmentCreated(array $payload): JsonResponse
    {
        $shopifyOrderId = (string) ($payload['order_id'] ?? '');
        $order = $shopifyOrderId !== ''
            ? Order::where('shopify_order_id', $shopifyOrderId)->first()
            : null;

        if ($order === null) {
            return response()->json(['status' => 'order_not_found'], 200);
        }

        $order->update([
            'status_local' => 'fulfilled',
            'status_shopify' => 'fulfilled',
            'shopify_fulfillment_id' => (string) ($payload['id'] ?? ''),
            'tracking_number' => $payload['tracking_number'] ?? null,
            'tracking_url' => $payload['tracking_url'] ?? null,
            'last_synced_at' => now(),
        ]);

        Log::channel('single')->info('[Webhook] Fulfillment created', [
            'order_id' => $order->id,
            'shopify_order_id' => $shopifyOrderId,
            'tracking' => $order->tracking_number,
        ]);

        return response()->json(['status' => 'fulfilled', 'order_id' => $order->id]);
    }

    /**
     * fulfillments/update — Actualización de tracking o estado del fulfillment.
     */
    private function handleFulfillmentUpdated(array $payload): JsonResponse
    {
        $shopifyOrderId = (string) ($payload['order_id'] ?? '');
        $order = $shopifyOrderId !== ''
            ? Order::where('shopify_order_id', $shopifyOrderId)->first()
            : null;

        if ($order === null) {
            return response()->json(['status' => 'order_not_found'], 200);
        }

        $updates = ['last_synced_at' => now()];

        if (! empty($payload['tracking_number'])) {
            $updates['tracking_number'] = $payload['tracking_number'];
        }
        if (! empty($payload['tracking_url'])) {
            $updates['tracking_url'] = $payload['tracking_url'];
        }

        // Si el fulfillment tiene status "success", marcar como entregado.
        $fulfillmentStatus = $payload['status'] ?? null;
        if ($fulfillmentStatus === 'success') {
            $updates['status_local'] = 'fulfilled';
            $updates['status_shopify'] = 'fulfilled';
        }

        $order->update($updates);

        Log::channel('single')->info('[Webhook] Fulfillment updated', [
            'order_id' => $order->id,
            'fulfillment_status' => $fulfillmentStatus,
            'tracking' => $order->tracking_number,
        ]);

        return response()->json(['status' => 'updated', 'order_id' => $order->id]);
    }

    private function handleFulfilled(array $payload): JsonResponse
    {
        $order = $this->findOrder($payload);

        if ($order === null) {
            return response()->json(['status' => 'order_not_found'], 200);
        }

        $fulfillment = $payload['fulfillments'][0] ?? null;

        $order->update([
            'status_local' => 'fulfilled',
            'status_shopify' => 'fulfilled',
            'shopify_fulfillment_id' => $fulfillment ? (string) $fulfillment['id'] : null,
            'tracking_number' => $fulfillment['tracking_number'] ?? null,
            'tracking_url' => $fulfillment['tracking_url'] ?? null,
            'last_synced_at' => now(),
        ]);

        Log::channel('single')->info('[Webhook] Order fulfilled', [
            'order_id' => $order->id,
            'shopify_order_id' => $order->shopify_order_id,
            'tracking' => $order->tracking_number,
        ]);

        return response()->json(['status' => 'fulfilled', 'order_id' => $order->id]);
    }

    private function handleCancelled(array $payload): JsonResponse
    {
        $order = $this->findOrder($payload);

        if ($order === null) {
            return response()->json(['status' => 'order_not_found'], 200);
        }

        $order->update([
            'status_local' => 'cancelled',
            'status_shopify' => 'cancelled',
            'cancellation_reason' => $payload['cancel_reason'] ?? 'shopify_webhook',
            'last_synced_at' => now(),
        ]);

        Log::channel('single')->info('[Webhook] Order cancelled', [
            'order_id' => $order->id,
            'reason' => $order->cancellation_reason,
        ]);

        return response()->json(['status' => 'cancelled', 'order_id' => $order->id]);
    }

    private function handleUpdated(array $payload): JsonResponse
    {
        $order = $this->findOrder($payload);

        if ($order === null) {
            return response()->json(['status' => 'order_not_found'], 200);
        }

        $financialStatus = $payload['financial_status'] ?? null;
        $fulfillmentStatus = $payload['fulfillment_status'] ?? null;

        // Solo actualizamos status_shopify como campo de seguimiento genérico.
        // No sobreescribimos status_local para no interferir con fulfilled/cancelled
        // que ya se manejan por sus propios topics.
        $order->update([
            'status_shopify' => $fulfillmentStatus ?? $financialStatus ?? $order->status_shopify,
            'last_synced_at' => now(),
        ]);

        return response()->json(['status' => 'updated', 'order_id' => $order->id]);
    }

    private function findOrder(array $payload): ?Order
    {
        $shopifyOrderId = (string) ($payload['id'] ?? '');

        if ($shopifyOrderId === '') {
            return null;
        }

        return Order::where('shopify_order_id', $shopifyOrderId)->first();
    }
}
