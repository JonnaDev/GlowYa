<?php

declare(strict_types=1);

namespace App\Modules\Shopify\Providers;

use App\Modules\Shopify\Services\ShopifyClient;
use Illuminate\Support\ServiceProvider;

class ShopifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ShopifyClient::class, function ($app): ShopifyClient {
            $config = $app['config']->get('services.shopify');

            return new ShopifyClient(
                store: $config['store'],
                apiVersion: $config['api_version'],
                accessToken: $config['access_token'],
            );
        });
    }

    public function boot(): void
    {
        // Cuando el módulo crezca, cargar acá:
        // $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        // $this->loadViewsFrom(__DIR__.'/../Resources/views', 'shopify');
        // $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
