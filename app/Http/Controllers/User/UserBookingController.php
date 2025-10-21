<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\BookingPassanger;

class UserBookingController extends Controller
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

        $flight = Flight::with('seatAvailability')->findOrFail($validated['flight_id']);

        $requestedSeats = count($validated['passengers']);

        // Generate a unique booking code
        $bookingCode = 'BK-' . strtoupper(Str::random(6));

        // Use transaction to avoid race conditions. We will check available seats first.
        try {
            $booking = \DB::transaction(function () use ($flight, $validated, $requestedSeats, $bookingCode) {
                // Refresh flight seat availability inside transaction
                $flight->refresh();

                $available = $flight->available_seats;
                if ($requestedSeats > $available) {
                    throw new \Exception('Not enough seats available.');
                }

                $booking = Booking::create([
                    'user_id' => Auth::id(),
                    'flight_id' => $flight->id,
                    'booking_code' => $bookingCode,
                    'booking_status' => 'confirmed', // directly confirm for simplicity
                    'number_of_seats' => $requestedSeats,
                    'total_price' => $requestedSeats * $flight->price,
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

                return $booking;
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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
