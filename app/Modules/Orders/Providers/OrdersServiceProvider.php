<?php

declare(strict_types=1);

namespace App\Modules\Orders\Providers;

use App\Modules\Orders\Console\Commands\CreateTestOrderCommand;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
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
                CreateTestOrderCommand::class,
            ]);
        }

        // Cuando el módulo crezca, cargar acá:
        // $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../Resources/views', 'orders');
    }
}
