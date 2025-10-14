<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\BookingPassanger;

class BookingController extends Controller
{
    /**
     * Show user's booking list.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passengers'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('pages.user.bookings.index', compact('bookings'));
    }

    /**
     * Show booking creation form for selected flight.
     */
    public function create(Request $request)
    {
        $flightId = $request->get('flight_id');
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('status', 'active')
            ->findOrFail($flightId);

        return view('pages.user.bookings.create', compact('flight'));
    }

    /**
     * Store a new booking with passenger data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.email' => 'nullable|email|max:255',
            'passengers.*.seat_number' => 'nullable|string|max:10',
        ]);

        $flight = Flight::findOrFail($validated['flight_id']);

        // Generate a unique booking code
        $bookingCode = 'BK-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'flight_id' => $flight->id,
            'booking_code' => $bookingCode,
            'booking_status' => 'pending',
            'total_price' => count($validated['passengers']) * $flight->price,
        ]);

        // Save passengers
        foreach ($validated['passengers'] as $p) {
            BookingPassanger::create([
                'booking_id' => $booking->id,
                'name' => $p['name'],
                'email' => $p['email'] ?? null,
                'seat_number' => $p['seat_number'] ?? null,
            ]);
        }

        return redirect()->route('user.bookings.show', $booking->id)
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Show booking details.
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passengers']);

        return view('pages.user.bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking if still pending.
     */
    public function cancel(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->booking_status !== 'pending') {
            return back()->with('error', 'Booking can no longer be cancelled.');
        }

        $booking->update(['booking_status' => 'cancelled']);

        return redirect()->route('user.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Ensure the booking belongs to the logged-in user.
     */
    private function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}
