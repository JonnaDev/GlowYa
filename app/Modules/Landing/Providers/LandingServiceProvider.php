<?php

declare(strict_types=1);

namespace App\Modules\Landing\Providers;

use Illuminate\Support\ServiceProvider;

class LandingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        // Cuando el módulo crezca, cargar acá:
        // $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../Resources/views', 'landing');
    }
}
