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
        // Drop table lama kalau masih ada
        Schema::dropIfExists('flight_seat_availabilities');

        // Update kolom di tabel flights (opsional, tambahin tracking)
        Schema::table('flights', function (Blueprint $table) {
            if (!Schema::hasColumn('flights', 'booked_seats')) {
                $table->integer('booked_seats')->default(0)->after('total_seats');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn('booked_seats');
        });

        Schema::create('flight_seat_availabilities', function (Blueprint $table) {
            $table->unsignedBigInteger('flight_id')->primary();
            $table->integer('available_seats')->default(0);
            $table->timestamps();
        });
    }
};
