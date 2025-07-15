<?php

use App\Http\Controllers\CategoryController;
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
    // Admin dashboard views
    Route::get('/customers', fn () => Inertia::render('Admin/Customers'))->name('customers');
    Route::get('/listings', fn () => Inertia::render('Admin/Listings'))->name('listings');

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
