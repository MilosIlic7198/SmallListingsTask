<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned the "web" middleware group.
|
*/

// Load public routes
require __DIR__ . '/public.php';

// Load customer routes
require __DIR__ . '/customer.php';

// Load admin routes
require __DIR__ . '/admin.php';

// Load authentication routes (Laravel Breeze or similar)
require __DIR__ . '/auth.php';
