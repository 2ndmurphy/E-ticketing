<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookingPassanger;

class BookingPassengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookingId = config('test.booking_id');

        $passengers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'seat_number' => '12A'],
            ['name' => 'Jane Doe', 'email' => 'jane@example.com', 'seat_number' => '12B'],
        ];

        foreach ($passengers as $p) {
            BookingPassanger::create([
                'booking_id' => $bookingId,
                'name' => $p['name'],
                'email' => $p['email'],
                'seat_number' => $p['seat_number'],
            ]);
        }
    }
}
