<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ListingController::class, 'index'])->name('home');

// Public API-style routes for categories and nested subcategories
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/all', [CategoryController::class, 'all'])->name('all'); // all categories, returns JSON
    Route::get('/{category}/subcategories', [CategoryController::class, 'subcategories'])->name('subcategories'); // subcategories of a category
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // General dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/listings', [ListingController::class, 'create'])->name('create');
    Route::post('/listings', [ListingController::class, 'store'])->name('store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/customers', function () {
        return Inertia::render('Admin/Customers');
    })->name('customers');

    // Admin-only category management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'create'])->name('create'); // view in admin
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Admin listing view
    Route::get('/listings', function () {
        return Inertia::render('Admin/Listings');
    })->name('listings');
});

require __DIR__.'/auth.php';
