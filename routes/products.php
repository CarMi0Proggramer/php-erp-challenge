<?php

use App\Enums\Role;
use App\Http\Controllers\Products\ProductDescriptionImageController;
use App\Http\Controllers\Products\ProductImageController;
use App\Http\Controllers\Products\ProductsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductsController::class)
        ->name('index', 'products')
        ->middlewareFor(['index', 'create', 'edit'], 'role:'.implode(',', [
            Role::ADMIN->value,
            Role::OPERATOR->value,
        ]));

    Route::prefix('products')->group(function () {
        Route::apiResource(
            '{product}/description-images',
            ProductDescriptionImageController::class
        )->except(['index', 'update', 'show']);

        Route::apiResource('{product}/images', ProductImageController::class)
            ->except(['index', 'update', 'show']);

        Route::post('{product}/images/{image}/primary', [
            ProductImageController::class,
            'markAsPrimary',
        ])->name('images.primary');
    });
});
