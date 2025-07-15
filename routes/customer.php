<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Routes accessible only to users with the 'customer' role.
|
*/

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    // Customer listings (custom index route)
    Route::get('/listings', [ListingController::class, 'customerListings'])->name('listings');

    // Custom POST route for updating a listing
    Route::post('/listings/{listing}/update', [ListingController::class, 'update'])->name('listings.update');

    // Resource routes for listings (excluding index)
    Route::resource('listings', ListingController::class)->only([
        'create', 'store', 'edit', 'destroy'
    ])->names([
        'create' => 'listings.create',
        'store' => 'listings.store',
        'edit' => 'listings.edit',
        'destroy' => 'listings.destroy',
    ]);
});
