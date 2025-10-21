<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingObserver
{
    /**
     * Handle events after a booking is created.
     */
    public function created(Booking $booking)
    {
        // Only decrease availability for confirmed/completed bookings.
        if (in_array($booking->booking_status, ['confirmed', 'completed'])) {
            DB::transaction(function () use ($booking) {
                DB::table('flight_seat_availabilities')
                    ->where('flight_id', $booking->flight_id)
                    ->decrement('available_seats', $booking->number_of_seats);
            });
        }
    }

    public function updated(Booking $booking)
    {
        // Handle status changes or number_of_seats changes.
        $original = $booking->getOriginal();

        $wasCounted = in_array($original['booking_status'] ?? null, ['confirmed', 'completed']);
        $isCounted = in_array($booking->booking_status, ['confirmed', 'completed']);

        DB::transaction(function () use ($booking, $original, $wasCounted, $isCounted) {
            $diff = 0;

            if ($wasCounted && $isCounted) {
                // If both counted, adjust by seats diff
                $diff = ($booking->number_of_seats ?? 0) - ($original['number_of_seats'] ?? 0);
                if ($diff > 0) {
                    DB::table('flight_seat_availabilities')->where('flight_id', $booking->flight_id)->decrement('available_seats', $diff);
                } elseif ($diff < 0) {
                    DB::table('flight_seat_availabilities')->where('flight_id', $booking->flight_id)->increment('available_seats', abs($diff));
                }
            } elseif (!$wasCounted && $isCounted) {
                // Now counted -> decrement
                DB::table('flight_seat_availabilities')->where('flight_id', $booking->flight_id)->decrement('available_seats', $booking->number_of_seats ?? 0);
            } elseif ($wasCounted && !$isCounted) {
                // No longer counted -> increment
                DB::table('flight_seat_availabilities')->where('flight_id', $booking->flight_id)->increment('available_seats', $original['number_of_seats'] ?? 0);
            }
        });
    }

    public function deleted(Booking $booking)
    {
        // If deleting a counted booking, restore seats
        if (in_array($booking->booking_status, ['confirmed', 'completed'])) {
            DB::transaction(function () use ($booking) {
                DB::table('flight_seat_availabilities')
                    ->where('flight_id', $booking->flight_id)
                    ->increment('available_seats', $booking->number_of_seats ?? 0);
            });
        }
    }
}
