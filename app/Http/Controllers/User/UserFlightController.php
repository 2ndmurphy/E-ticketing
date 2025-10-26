<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserFlightController extends Controller
{
    /**
     * Display the flight search form and optionally search results.
     */
    public function index(Request $request)
    {
        $airports = Airport::all();

        $flights = collect();
        $query = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('status', 'active');

        if ($request->filled(['from', 'to'])) {
            $query->where('departure_airport_id', $request->from)
                ->where('arrival_airport_id', $request->to);
        }

        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }

        // Kalau search filters (from+to) tersedia, biarkan tetap ada (paginate 10).
        // Jika tidak tunjukkan  5 penerbangan aktif pertama menggunakan paginate(5).
        if ($request->filled(['from', 'to'])) {
            $flights = $query->orderBy('departure_time')->paginate(10);
        } else {
            $flights = $query->orderBy('departure_time')->paginate(5);
        }

        return view('pages.user.flights.index', compact('airports', 'flights'));
    }

    /**
     * Show a detailed view of a specific flight before booking.
     */
    public function show($id)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('status', 'active')
            ->findOrFail($id);
        $availableSeats = $flight->available_seats;

        // Mencegah user dari membuat booking lain sementara mereka memiliki booking yang tertunda.
        $hasPending = Booking::where('user_id', Auth::id())
            ->where('booking_status', 'pending')
            ->exists();

        return view('pages.user.flights.show', compact('flight', 'availableSeats', 'hasPending'));
    }
}
