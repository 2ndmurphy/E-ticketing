<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite does not support OR REPLACE
            DB::statement("
                CREATE VIEW flight_seat_availability AS
                SELECT 
                    f.id AS flight_id,
                    f.total_seats - COALESCE(SUM(b.number_of_seats), 0) AS available_seats
                FROM flights f
                LEFT JOIN bookings b 
                ON f.id = b.flight_id 
                AND b.booking_status IN ('confirmed','completed')
                GROUP BY f.id, f.total_seats;
            ");
        } else {
            // MySQL or PostgreSQL
            DB::statement("
                CREATE OR REPLACE VIEW flight_seat_availability AS
                SELECT 
                    f.id AS flight_id,
                    f.total_seats - COALESCE(SUM(b.number_of_seats), 0) AS available_seats
                FROM flights f
                LEFT JOIN bookings b 
                ON f.id = b.flight_id 
                AND b.booking_status IN ('confirmed','completed')
                GROUP BY f.id, f.total_seats;
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS flight_seat_availability');
    }
};
