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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 100)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('flight_id')->constrained('flights');
            $table->integer('number_of_seats')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid ']);
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->datetime('paid_at')->nullable();
            $table->datetime('booking_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
