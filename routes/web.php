<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    AdminDashboardController,
    UserController,
    AirlineController,
    AirportController
};
use App\Http\Controllers\Airline\{
    AirlineFlightController,
    AirlineBookingController,
    AirlineProfileController,
    AirlinePassangerController
};
use App\Http\Controllers\Airline\AirlineDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\{
    UserDashboardController,
    UserFlightController,
    UserBookingController,
    UserProfileController
};

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
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('airlines', AirlineController::class);
        Route::resource('airports', AirportController::class);
    });

    // Maskapai Only
    Route::middleware(['role:maskapai'])->prefix('maskapai')->name('maskapai.')->group(function () {
        Route::get('/dashboard', [AirlineDashboardController::class, 'index'])->name('dashboard');

        Route::resource('flights', AirlineFlightController::class);
        Route::resource('bookings', AirlineBookingController::class);

        // Route::get('/bookings', [AirlineBookingController::class, 'index'])->name('bookings.index');
        // Route::get('/bookings/{booking}', [AirlineBookingController::class, 'show'])->name('bookings.show');
        // Route::put('/bookings/{booking}/update-status', [AirlineBookingController::class, 'update'])->name('bookings.update-status');

        Route::get('/passengers', [AirlinePassangerController::class, 'index'])->name('passengers.index');
        Route::get('/passengers/export/{flightId}', [AirlinePassangerController::class, 'export'])->name('passengers.export');

        Route::get('/profile/edit', [AirlineProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AirlineProfileController::class, 'update'])->name('profile.update');
    });

    // User Only
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        Route::get('/flights', [UserFlightController::class, 'index'])->name('flights.index');
        Route::get('/flights/{id}', [UserFlightController::class, 'show'])->name('flights.show');

        Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create/{flight}', [UserBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/payment', [UserBookingController::class, 'paymentBooking'])->name('bookings.payment');
        Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

        Route::get('/profile/password', [UserProfileController::class, 'editPassword'])->name('profile.password.edit');
        Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});

require __DIR__ . '/auth.php';
