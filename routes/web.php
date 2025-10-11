<?php

use App\Http\Controllers\Airline\FlightController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\AdminController;
use App\Http\Controllers\User\MaskapaiController;
use App\Http\Controllers\User\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // Maskapai Only
    Route::middleware(['role:maskapai'])->prefix('maskapai')->name('maskapai.')->group(function () {
        Route::get('/dashboard', [MaskapaiController::class, 'index'])->name('maskapai.dashboard');

        Route::resource('flights', FlightController::class);
    });

    // User Only
    Route::middleware(['role:user'])->group(function () {
        Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    });
});

require __DIR__.'/auth.php';
