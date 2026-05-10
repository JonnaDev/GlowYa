<?php

use App\Http\Controllers\ProfileController;
use App\Modules\Catalog\Http\Controllers\ImportController;
use App\Modules\Landing\Http\Controllers\Admin\LandingController as AdminLandingController;
use App\Modules\Landing\Http\Controllers\PublicLandingController;
use App\Modules\Orders\Http\Controllers\PublicOrderController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Catálogo
    Route::get('/catalog/import', ImportController::class)->name('catalog.import');
    Route::post('/catalog/import/sync', [ImportController::class, 'sync'])->name('catalog.import.sync');
    Route::get('/catalog/categories', fn () => Inertia::render('Catalog/Categories'))
        ->name('catalog.categories');

    // Admin: gestión de landings (toggle activo/inactivo)
    Route::get('/admin/landings', [AdminLandingController::class, 'index'])->name('admin.landings.index');
    Route::post('/admin/landings/{landing:slug}/toggle', [AdminLandingController::class, 'toggle'])
        ->name('admin.landings.toggle');

    // Admin
    Route::get('/orders', fn () => Inertia::render('Orders/Index'))
        ->name('orders.index');
    Route::get('/products', fn () => Inertia::render('Products/Index'))
        ->name('products.index');
    Route::get('/sales', fn () => Inertia::render('Sales/Index'))
        ->name('sales.index');
    Route::get('/analytics', fn () => Inertia::render('Analytics/Index'))
        ->name('analytics.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Landings públicas dinámicas (resueltas por slug en BD; respetan is_active).
// Se declaran al final para no pisar rutas anteriores.
Route::post('/{slug}/order', [PublicOrderController::class, 'store'])
    ->where('slug', '[a-z0-9-]+')
    ->name('landing.order');

Route::get('/{slug}', [PublicLandingController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('landing.show');
