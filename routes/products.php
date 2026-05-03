<?php

use App\Enums\Role;
use App\Http\Controllers\Products\ProductDescriptionImageController;
use App\Http\Controllers\Products\ProductImageController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Products\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductsController::class)
        ->except('show')
        ->name('index', 'products')
        ->middlewareFor(['index', 'create', 'edit'], 'role:'.implode(',', [
            Role::ADMIN->value,
            Role::OPERATOR->value,
        ]));

    Route::prefix('products/{product}')->group(function () {
        Route::apiResource(
            'description-images',
            ProductDescriptionImageController::class
        )->except(['index', 'update', 'show']);

        Route::apiResource('images', ProductImageController::class)
            ->except(['index', 'update', 'show']);

        Route::post('images/{image}/primary', [
            ProductImageController::class,
            'markAsPrimary',
        ])->name('images.primary');

        Route::get('stock', [StockMovementController::class, 'index'])
            ->name('products.stock')
            ->middleware('role:'.implode(',', [
                Role::ADMIN->value,
                Role::OPERATOR->value,
            ]));

        Route::post('stock-movements', [StockMovementController::class, 'store'])
            ->name('products.stock.store');
    });
});
