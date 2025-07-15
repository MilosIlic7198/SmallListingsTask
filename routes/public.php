<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Routes accessible to all users, including guests.
|
*/

Route::get('/', [ListingController::class, 'index'])->name('index');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Public API-style routes for categories
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/all', [CategoryController::class, 'all'])->name('all');
    Route::get('/{category}/subcategories', [CategoryController::class, 'subcategories'])->name('subcategories');
});
