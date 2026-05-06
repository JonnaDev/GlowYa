<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers;

use App\Modules\Catalog\Console\Commands\ImportProductsCommand;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportProductsCommand::class,
            ]);
        }

        // Cuando el módulo crezca, cargar acá:
        // $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../Resources/views', 'catalog');
    }
}
