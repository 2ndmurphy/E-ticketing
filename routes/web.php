<?php

use App\Http\Controllers\Airline\{AirlineFlightController, AirlineBookingController, AirlineProfileController, AirlinePassangerController};
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\{UserFlightController, UserBookingController, UserProfileController};
use App\Http\Controllers\User\AdminController;

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

Route::middleware(['auth'])->group(function () {
    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // Maskapai Only
    Route::middleware(['role:maskapai'])->prefix('maskapai')->name('maskapai.')->group(function () {
        // Route::get('/dashboard', [MaskapaiController::class, 'index'])->name('dashboard');

        Route::resource('flights', AirlineFlightController::class);

        Route::get('/bookings', [AirlineBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AirlineBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/update-status', [AirlineBookingController::class, 'updateStatus'])->name('bookings.update-status');

        Route::get('/passengers', [AirlinePassangerController::class, 'index'])->name('passengers.index');
        Route::get('/passengers/export/{flightId}', [AirlinePassangerController::class, 'export'])->name('passengers.export');

        Route::get('/profile', [AirlineProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [AirlineProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AirlineProfileController::class, 'update'])->name('profile.update');
    });

    // User Only
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        // Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

        Route::get('/flights', [UserFlightController::class, 'index'])->name('flights.index');
        Route::get('/flights/{id}', [UserFlightController::class, 'show'])->name('flights.show');

        Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [UserBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
        // Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

        Route::get('/profile/password', [UserProfileController::class, 'editPassword'])->name('profile.password.edit');
        Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});

require __DIR__ . '/auth.php';
