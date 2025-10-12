<?php

namespace App\Http\Controllers\Airline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\BookingPassanger;
use Illuminate\Support\Facades\Auth;

class PassangerController extends Controller
{
    /**
     * Display all passengers for the airline admin’s flights.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAirlineAdmin()) {
            abort(403, 'Access denied.');
        }

        $airline = Airline::where('managed_by_user_id', $user->id)->firstOrFail();

        // Optional filter by flight
        $flights = Flight::where('airline_id', $airline->id)
            ->with(['departureAirport', 'arrivalAirport'])
            ->orderBy('departure_time', 'desc')
            ->get();

        $selectedFlightId = $request->get('flight_id');

        $passengers = collect();

        if ($selectedFlightId) {
            $flight = Flight::where('id', $selectedFlightId)
                ->where('airline_id', $airline->id)
                ->firstOrFail();

            $passengers = BookingPassanger::whereHas('booking', function ($query) use ($flight) {
                $query->where('flight_id', $flight->id)
                    ->whereIn('booking_status', ['confirmed', 'completed']);
            })->with('booking')->orderBy('name')->get();
        }

        // return view('airline.passengers.index', compact('airline', 'flights', 'passengers', 'selectedFlightId'));
    }

    /**
     * Export passenger manifest for a specific flight.
     */
    public function export($flightId)
    {
        $user = Auth::user();
        $airline = Airline::where('managed_by_user_id', $user->id)->firstOrFail();

        $flight = Flight::where('id', $flightId)
            ->where('airline_id', $airline->id)
            ->with(['departureAirport', 'arrivalAirport'])
            ->firstOrFail();

        $passengers = BookingPassanger::whereHas('booking', function ($q) use ($flight) {
            $q->where('flight_id', $flight->id)
              ->whereIn('booking_status', ['confirmed', 'completed']);
        })->get();

        $csv = "Passenger Name,Email,Seat Number,Booking Code\n";
        foreach ($passengers as $p) {
            $csv .= "{$p->name},{$p->email},{$p->seat_number},{$p->booking->booking_code}\n";
        }

        $filename = "manifest_{$flight->flight_number}.csv";

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
