<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSeatAvailability extends Model
{
    // Switch to a writable table implementation. If you prefer the read-only DB view,
    // set this back to 'flight_seat_availability' and adjust primaryKey/flags accordingly.
    protected $table = 'flight_seat_availabilities';
    protected $primaryKey = 'flight_id';
    public $incrementing = false; // flight_id is not auto-incrementing
    public $timestamps = true;    // migration creates timestamps

    protected $fillable = [
        'flight_id',
        'available_seats'
    ];

    // Optional: link back to flight
    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }
}
