<?php

declare(strict_types=1);

namespace App\Modules\Shopify\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Orders\Models\Order;
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
 *  - orders/fulfilled   → status_local = fulfilled
 *  - orders/cancelled   → status_local = cancelled
 *  - orders/updated     → sincroniza status_shopify (campo genérico)
 */
class ShopifyWebhookController extends Controller
{
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
            'orders/fulfilled' => $this->handleFulfilled($payload),
            'fulfillments/create' => $this->handleFulfillmentCreated($payload),
            'fulfillments/update' => $this->handleFulfillmentUpdated($payload),
            'orders/cancelled' => $this->handleCancelled($payload),
            'orders/updated' => $this->handleUpdated($payload),
            default => response()->json(['ignored' => true], 200),
        };
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
