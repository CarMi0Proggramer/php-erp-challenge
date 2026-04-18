<?php

use App\Enums\Role;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {
        Route::inertia('', 'Dashboard')->name('dashboard');
        Route::inertia('users', 'Users')
            ->name('dashboard.users')
            ->middleware('role:'.Role::ADMIN->value);

        // Route::inertia('orders', 'Orders')
        //     ->name('dashboard.orders')
        //     ->middleware('role:' . implode(',', [
        //         Role::ADMIN->value,
        //         Role::SELLER->value
        //     ]));

        // Route::inertia('products', 'Products')
        //     ->name('dashboard.products')
        //     ->middleware('role:' . implode(',', [
        //         Role::ADMIN->value,
        //         Role::OPERATOR->value
        //     ]));

        // Route::inertia('finances', 'Finances')
        //     ->name('dashboard.finances')
        //     ->middleware('role:' . implode(',', [
        //         Role::ADMIN->value,
        //         Role::ACCOUNTANT->value
        //     ]));
    });

require __DIR__.'/settings.php';
