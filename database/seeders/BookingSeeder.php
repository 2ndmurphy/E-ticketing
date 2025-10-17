<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'john@example.com')->first();
        $flight = Flight::first();

        $booking = Booking::create([
            'booking_code' => 'BK-' . strtoupper(Str::random(6)),
            'user_id' => $user->id,
            'flight_id' => $flight->id,
            'number_of_seats' => 2,
            'total_price' => $flight->price * 2,
            'payment_status' => 'unpaid',
            'booking_status' => 'pending',
            'paid_at' => now(),
            'booking_date' => now(),
        ]);

        // Link for passengers seeder
        config(['test.booking_id' => $booking->id]);
    }
}
