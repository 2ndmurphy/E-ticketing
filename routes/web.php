<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    UserController,
    AirlineController,
    AirportController
};
use App\Http\Controllers\Airline\{
    DashboardController as AirlineDashboard,
    AirlineFlightController,
    AirlineBookingController,
    AirlineProfileController,
    AirlinePassangerController
};
use App\Http\Controllers\User\{
    DashboardController as UserDashboard,
    UserFlightController,
    UserBookingController,
    UserProfileController
};
use App\Http\Controllers\ProfileController;

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
    Route::middleware(['role:admin'])->prefix('a')->name('admin.')->group(function () {
        Route::resource('/dashboard', AdminDashboard::class);
        Route::resource('users', UserController::class);
        Route::resource('airlines', AirlineController::class);
        Route::resource('airports', AirportController::class);
    });

    // Maskapai Only
    Route::middleware(['role:maskapai'])->prefix('m')->name('maskapai.')->group(function () {
        Route::resource('dashboard', AirlineDashboard::class)->only('index');
        Route::resource('flights', AirlineFlightController::class);
        Route::resource('bookings', AirlineBookingController::class);
        // Route::resource('profile', AirlineProfileController::class)->only(['edit', 'update']);

        Route::get('/passengers', [AirlinePassangerController::class, 'index'])->name('passengers.index');
        Route::get('/passengers/{flightId}/export', [AirlinePassangerController::class, 'export'])->name('passengers.export');
        
        Route::put('/bookings/{booking}/update-status', [AirlineBookingController::class, 'update'])->name('bookings.update-status');
        Route::get('/profile/edit', [AirlineProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AirlineProfileController::class, 'update'])->name('profile.update');
    });

    // User Only
    Route::middleware(['role:user'])->prefix('u')->name('user.')->group(function () {
        Route::resource('dashboard', UserDashboard::class)->only('index');
        Route::resource('flights', UserFlightController::class)->only(['index', 'show']);
        Route::resource('bookings', UserBookingController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('profile', UserProfileController::class)->only(['edit', 'update']);

        // Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        // Route::get('/bookings/create/{flight}', [UserBookingController::class, 'create'])->name('bookings.create');
        // Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
        // Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/payment', [UserBookingController::class, 'paymentBooking'])->name('bookings.payment');
        Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

        // Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        // Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

        Route::get('/profile/password', [UserProfileController::class, 'editPassword'])->name('profile.password.edit');
        Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});

require __DIR__ . '/auth.php';
