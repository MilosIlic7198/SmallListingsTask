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
});
