<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;

class PaymentController extends Controller
{
    /**
     * Show payment form for a specific booking.
     */
    public function create(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if (!in_array($booking->booking_status, ['pending', 'awaiting_payment'])) {
            return redirect()->route('user.bookings.show', $booking->id)
                ->with('error', 'Payment cannot be processed for this booking.');
        }

        // return view('user.payments.create', compact('booking'));
    }

    /**
     * Store payment proof and update booking status.
     */
    public function store(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
        }

        $booking->update([
            'booking_status' => 'payment_submitted',
            'payment_method' => $validated['payment_method'],
            'payment_proof' => $path,
            'paid_at' => now(),
        ]);

        return redirect()->route('user.bookings.show', $booking->id)
            ->with('success', 'Payment proof submitted. Awaiting confirmation.');
    }

    /**
     * Simulate instant payment success (for demo/testing).
     */
    public function simulateSuccess(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->booking_status !== 'pending') {
            return back()->with('error', 'Cannot simulate payment for this booking.');
        }

        $booking->update([
            'booking_status' => 'confirmed',
            'paid_at' => now(),
            'payment_method' => 'simulation',
        ]);

        return redirect()->route('user.bookings.show', $booking->id)
            ->with('success', 'Payment simulated successfully!');
    }

    /**
     * Ensure the booking belongs to the current user.
     */
    private function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}
