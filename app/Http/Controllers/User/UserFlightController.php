<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Airport;
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
        $query = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'seatAvailability'])
            ->where('status', 'active');

        if ($request->filled(['from', 'to'])) {
            $query->where('departure_airport_id', $request->from)
                ->where('arrival_airport_id', $request->to);
        }

        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }

        if ($request->filled(['from', 'to'])) {
            $flights = $query->orderBy('departure_time')->paginate(10);
        }

        return view('pages.user.flights.index', compact('airports', 'flights'));
    }

    /**
     * Show a detailed view of a specific flight before booking.
     */
    public function show($id)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'seatAvailability'])
            ->where('status', 'active')
            ->findOrFail($id);

        return view('pages.user.flights.show', compact('flight'));
    }
}
