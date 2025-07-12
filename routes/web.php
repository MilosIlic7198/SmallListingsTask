<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ListingController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    //Profile routes group.
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    //Customer and admin dashboard route.
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/my-listings', function () {
        return Inertia::render('Customer/Listings');
    })->name('customer.listings');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/customers', function () {
        return Inertia::render('Admin/Customers');
    })->name('admin.customers');
    Route::get('/categories', function () {
        return Inertia::render('Admin/Categories');
    })->name('admin.categories');
    Route::get('/listings', function () {
        return Inertia::render('Admin/Listings');
    })->name('admin.listings');
});

require __DIR__.'/auth.php';
