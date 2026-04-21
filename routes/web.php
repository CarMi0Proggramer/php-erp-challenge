<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::inertia('dashboard', 'Dashboard')
    ->name('dashboard')
    ->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
require __DIR__.'/users.php';
require __DIR__.'/products.php';
