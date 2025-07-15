<?php

use App\Http\Controllers\Auth\LoggedInUserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Authentication and Authenticated Routes
|--------------------------------------------------------------------------
|
| This file contains routes for user authentication (login, register, logout,
| password updates) and authenticated user actions (dashboard, profile).
| Routes are grouped by middleware and purpose for clarity.
|
*/

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
|
| Routes accessible to unauthenticated users for login and registration.
|
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/login', [LoggedInUserController::class, 'create'])->name('login');
    Route::post('/login', [LoggedInUserController::class, 'store'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| Routes accessible only to authenticated users, including authentication
| actions (logout, password updates) and user features (dashboard, profile).
|
*/
Route::middleware('auth')->group(function () {
    // Authentication actions
    Route::post('/logout', [LoggedInUserController::class, 'destroy'])->name('logout');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // Dashboard
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});
