<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flight_seat_availabilities', function (Blueprint $table) {
            $table->unsignedBigInteger('flight_id')->primary();
            $table->integer('available_seats')->default(0);
            $table->timestamps();
        });

        // Populate initial data from flights and bookings (works on MySQL/Postgres/SQLite)
        // available_seats = flights.total_seats - SUM(bookings.number_of_seats WHERE booking_status IN (...))
        $driver = DB::getDriverName();

        // Use a portable query to aggregate booked seats per flight
        $booked = DB::table('bookings')
            ->select('flight_id', DB::raw('COALESCE(SUM(number_of_seats),0) as booked'))
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->groupBy('flight_id');

        $flights = DB::table('flights')
            ->leftJoinSub($booked, 'b', function ($join) {
                $join->on('flights.id', '=', 'b.flight_id');
            })
            ->select('flights.id as flight_id', 'flights.total_seats', DB::raw('COALESCE(b.booked,0) as booked'))
            ->get();

        $inserts = [];
        foreach ($flights as $f) {
            $available = max(0, intval($f->total_seats) - intval($f->booked));
            $inserts[] = [
                'flight_id' => $f->flight_id,
                'available_seats' => $available,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($inserts)) {
            DB::table('flight_seat_availabilities')->insert($inserts);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_seat_availabilities');
    }
};
