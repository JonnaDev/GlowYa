<?php

use App\Http\Controllers\ProfileController;
use App\Modules\Catalog\Http\Controllers\ImportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Catálogo
    Route::get('/catalog/import', ImportController::class)->name('catalog.import');
    Route::post('/catalog/import/sync', [ImportController::class, 'sync'])->name('catalog.import.sync');
    Route::get('/catalog/categories', fn () => Inertia::render('Catalog/Categories'))
        ->name('catalog.categories');

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
