<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes accessible only to users with the 'admin' role.
|
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Listings view
    Route::resource('listings', ListingController::class)->only([
        'index'
    ])->names([
        'index' => 'listings.index',
    ]);

    // Category management
    Route::resource('customers', UserController::class)->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ])->names([
        'index' => 'customers.index',
        'create' => 'customers.create',
        'store' => 'customers.store',
        'edit' => 'customers.edit',
        'update' => 'customers.update',
        'destroy' => 'customers.destroy',
    ]);
    // Restore a soft-deleted customer
    Route::patch('customers/{customer}/restore', [UserController::class, 'restore'])->name('customers.restore')->withTrashed();

    // Category management
    Route::resource('categories', CategoryController::class)->only([
        'create', 'store', 'update', 'destroy'
    ])->names([
        'create' => 'categories.create',
        'store' => 'categories.store',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
});
