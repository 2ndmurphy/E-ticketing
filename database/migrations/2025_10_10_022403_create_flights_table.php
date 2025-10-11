<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained('airlines');
            $table->string('flight_number', 50)->unique();
            $table->foreignId('departure_airport_id')->constrained('airports');
            $table->foreignId('arrival_airport_id')->constrained('airports');
            $table->datetime('departure_time')->useCurrent();
            $table->datetime('arrival_time')->useCurrent();
            $table->decimal('price', 10, 2);
            $table->integer('total_seats');
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
