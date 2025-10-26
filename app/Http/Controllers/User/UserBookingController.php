<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BookingHistory;
use App\Models\FlightSeatAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\BookingPassanger;
use Illuminate\Support\Facades\DB;

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
    public function create(Flight $flight)
    {
        Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('status', 'active')
            ->findOrFail($flight->id);

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

        // Pastikan kita ambil flight berdasarkan flight_id (bukan memanggil find pada instance route-bound)
        $flight = Flight::findOrFail($validated['flight_id']);

        // Mencegah user dari membuat booking lain sementara mereka memiliki booking yang tertunda.
        $hasPending = Booking::where('user_id', Auth::id())
            ->where('booking_status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Anda sudah memiliki booking yang tertunda. Silakan selesaikan pembayaran atau batalkan sebelum membuat booking baru.');
        }

        $requestedSeats = count($validated['passengers']);
        $availableSeats = $flight->available_seats;

        if ($requestedSeats > $availableSeats) {
            return back()->with('error', 'Jumlah penumpang melebihi kursi yang tersedia!');
        }

        $bookingCode = 'BK-' . strtoupper(Str::random(6));

        try {
            $booking = DB::transaction(function () use ($flight, $validated, $requestedSeats, $bookingCode) {
                $booking = Booking::create([
                    'booking_code' => $bookingCode,
                    'user_id' => Auth::id(),
                    'flight_id' => $flight->id,
                    'booking_status' => 'pending',
                    'number_of_seats' => $requestedSeats,
                    'total_price' => $requestedSeats * $flight->price,
                    'payment_status' => 'unpaid',
                ]);

                foreach ($validated['passengers'] as $p) {
                    BookingPassanger::create([
                        'booking_id' => $booking->id,
                        'name' => $p['name'],
                        'email' => $p['email'] ?? null,
                        'seat_number' => $p['seat_number'] ?? null,
                    ]);
                }

                BookingHistory::create([
                    'booking_id' => $booking->id,
                    'status' => 'pending',
                    'notes' => 'Booking created and pending payment.',
                ]);

                // Optional: update cached booked seats
                $flight->booked_seats = $flight->bookings()
                    ->whereIn('booking_status', ['pending', 'confirmed'])
                    ->sum('number_of_seats');
                $flight->save();

                return $booking;
            });
        } catch (\Exception $e) {
            // debug: tampilkan pesan error selama dev
            dd($e->getMessage(), $e->getTraceAsString());
            return back()->with('error', 'Booking Gagal: ' . $e->getMessage());
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

    public function paymentBooking(Booking $booking)
    {
        // Pastikan booking milik user yang login
        if ($booking->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah sudah dibayar
        if ($booking->payment_status === 'paid') {
            return back()->with('info', 'Booking ini sudah dibayar.');
        }

        try {
            $booking->update(
                [
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]
            );

            $booking->histories()->create([
                'status' => 'paid',
                'notes' => 'Payment completed by user.'
            ]);

            return back()->with('success', 'Pembayaran berhasil! Tunggu konfirmasi dari maskapai.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
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
