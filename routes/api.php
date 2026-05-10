<?php

use App\Modules\Shopify\Http\Controllers\ShopifyWebhookController;
use App\Modules\Shopify\Http\Middleware\VerifyShopifyWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Shopify webhooks — verificados con HMAC-SHA256, sin auth/CSRF.
Route::post('/webhooks/shopify', [ShopifyWebhookController::class, 'handle'])
    ->middleware(VerifyShopifyWebhook::class)
    ->name('webhooks.shopify');
