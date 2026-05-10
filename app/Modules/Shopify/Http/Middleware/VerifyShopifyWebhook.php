<?php

declare(strict_types=1);

namespace App\Modules\Shopify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el webhook viene de Shopify usando HMAC-SHA256.
 *
 * Los webhooks creados desde Settings → Notifications → Webhooks
 * se firman con el webhook signing secret de la tienda (no el API secret de la app).
 */
class VerifyShopifyWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $hmacHeader = $request->header('X-Shopify-Hmac-Sha256', '');

        // Prioriza SHOPIFY_WEBHOOK_SECRET; fallback al API secret de la app.
        $secret = config('services.shopify.webhook_secret')
            ?? config('services.shopify.api_secret');

        $payload = $request->getContent();

        $computed = base64_encode(
            hash_hmac('sha256', $payload, $secret, binary: true)
        );

        if (! hash_equals($computed, $hmacHeader)) {
            abort(401, 'Invalid webhook signature.');
        }

        return $next($request);
    }
}
