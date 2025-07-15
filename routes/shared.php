<?php

use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Shared Routes
|--------------------------------------------------------------------------
|
| Routes accessible to both 'admin' and 'customer' roles.
| Use this for features shared between these roles.
|
*/

Route::middleware(['auth', 'role:admin,customer'])->prefix('shared')->name('shared.')->group(function () {

    // For some fucking reason image upload does not work on PUT and PATCH so POST for updating a listing!
    Route::post('/listings/{listing}/update', [ListingController::class, 'update'])->name('listings.update');

    // Listing routes shared between admin & customer
    Route::resource('listings', ListingController::class)->only([
        'create', 'store', 'edit', 'destroy'
    ])->names([
        'create' => 'listings.create',
        'store' => 'listings.store',
        'edit' => 'listings.edit',
        'destroy' => 'listings.destroy'
    ]);
});
