<?php

use App\Enums\Role;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', [UsersController::class, 'index'])
        ->middleware('role:'.Role::ADMIN->value)
        ->name('users');

    Route::apiResource('users', UsersController::class)->except('index');
});
