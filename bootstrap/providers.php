<?php

use App\Modules\Catalog\Providers\CatalogServiceProvider;
use App\Modules\Landing\Providers\LandingServiceProvider;
use App\Modules\Orders\Providers\OrdersServiceProvider;
use App\Modules\Shopify\Providers\ShopifyServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,

    // Modules
    ShopifyServiceProvider::class,
    CatalogServiceProvider::class,
    OrdersServiceProvider::class,
    LandingServiceProvider::class,
];
