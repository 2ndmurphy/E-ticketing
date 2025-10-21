<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSeatAvailability extends Model
{
    protected $table = 'flight_seat_availabilities';
    protected $primaryKey = 'flight_id';
    public $incrementing = false;
    public $timestamps = true;

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
