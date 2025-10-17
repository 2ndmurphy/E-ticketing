<?php

namespace App\Http\Controllers\Airline;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AirlineFlightController extends Controller
{
    /**
     * Display a listing of the flights for the logged-in airline admin.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isMaskapai()) {
            return abort(403);
        }

        $airline = Airline::where('manage_by_user_id', $user->id)->first();

        $flights = Flight::with(['departureAirport', 'arrivalAirport', 'seatAvailability'])
            ->where('airline_id', $airline->id)
            ->orderBy('departure_time', 'asc')
            ->paginate(10);

        return view('pages.airline.flights.index', compact('airline', 'flights'));
    }

    /**
     * Show the form for creating a new flight.
     */
    public function create()
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->first();

        if (!$airline) {
            flash()->use('theme.neon')->warning('Please complete your airline profile before creating flights.');
            return redirect()->route('maskapai.profile.edit');
        }
        $airports = Airport::query()->orderBy('city')->get();

        return view('pages.airline.flights.create', compact('airline', 'airports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->first();

        if (!$airline) {
            flash()->use('theme.neon')->warning('Please complete your airline profile before creating flights.');
            return redirect()->route('maskapai.profile.edit');
        }

        $validated = $request->validate([
            'flight_number' => 'required|string|max:100|unique:flights',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
        ]);

        $validated['airline_id'] = $airline->id;
        $validated['created_at'] = now();
        $validated['update_at'] = now();

        Flight::create($validated);

        return redirect()->route('maskapai.flights.index')
            ->with('success', 'Flight created successfully!');
    }

    /**
     * Display the specified flight.
     */
    public function show(Flight $flight)
    {
        $flight->load('departureAirport', 'arrivalAirport', 'seatAvailability');

        return view('pages.airline.flights.show', compact('flight'));
    }

    /**
     * Show the form for editing a specific flight.
     */
    public function edit(Flight $flight)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->first();

        if (!$airline) {
            flash()->use('theme.neon')->warning('Please complete your airline profile before editing flights.');
            return redirect()->route('maskapai.profile.edit');
        }

        if ($flight->airline_id !== $airline->id) {
            return abort(403, 'You do not have permission to edit this flight.');
        }

        $airports = Airport::query()->orderBy('city')->get();

        return view('pages.airline.flights.edit', compact('flight', 'airline', 'airports'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flight $flight)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->first();

        if (!$airline) {
            flash()->use('theme.neon')->warning('Please complete your airline profile before updating flights.');
            return redirect()->route('maskapai.profile.edit');
        }

        if ($flight->airline_id !== $airline->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'flight_number' => 'required|string|max:100|unique:flights',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1',
        ]);

        $validated['airline_id'] = $airline->id;
        $validated['update_at'] = now();

        $flight->update($validated);
    }

    /**
     * Delete (or deactivate) a flight.
     */
    public function destroy(Flight $flight)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->first();

        if (!$airline) {
            flash()->use('theme.neon')->warning('Please complete your airline profile before managing flights.');
            return redirect()->route('maskapai.profile.edit');
        }

        if ($flight->airline_id !== $airline->id) {
            abort(403, 'Unauthorized action.');
        }

        $flight->update(['status'=> 'cancelled']);

        return redirect()->route('maskapai.flights.index')
            ->with('success', 'Flight cancelled successfully!');
    }
}
