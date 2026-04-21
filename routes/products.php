<?php

use App\Enums\Role;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products', [ProductsController::class, 'index'])
        ->name('products')
        ->middleware('role:'.implode(',', [
            Role::ADMIN->value,
            Role::OPERATOR->value,
        ]));
});
