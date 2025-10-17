<?php

namespace App\Http\Controllers\Airline;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AirlineBookingController extends Controller
{
    /**
     * Display all bookings for the airline admin’s flights.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isMaskapai()) {
            abort(403, 'Access denied.');
        }

        $airline = Airline::where('manage_by_user_id', $user->id)->firstOrFail();

        // Find all flights owned by this airline
        $flightIds = Flight::where('airline_id', $airline->id)->pluck('id');

        // Optional filters (status, keyword)
        $query = Booking::with(['flight.departureAirport', 'flight.arrivalAirport', 'passengers'])
            ->whereIn('flight_id', $flightIds);

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->where('booking_code', 'like', '%' . $request->keyword . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        // return view('airline.bookings.index', compact('bookings', 'airline'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show booking details (with passenger info).
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->firstOrFail();

        // Ensure booking belongs to this airline
        if ($booking->flight->airline_id !== $airline->id) {
            abort(403, 'Unauthorized booking access.');
        }

        $booking->load(['flight.departureAirport', 'flight.arrivalAirport', 'passengers']);

        // return view('airline.bookings.show', compact('booking', 'airline'));
    }

    /**
     * Update booking status (confirm, cancel, complete).
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->firstOrFail();

        if ($booking->flight->airline_id !== $airline->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        $booking->booking_status = $validated['status'];
        $booking->save();

        // Optionally: log the status change
        $booking->histories()->create([
            'user_id' => $user->id,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? 'Status changed by airline admin',
        ]);

        // return redirect()->route('airline.bookings.show', $booking->id)
        //     ->with('success', 'Booking status updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
