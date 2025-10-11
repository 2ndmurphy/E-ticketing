<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSeatAvailability extends Model
{
    protected $table = 'flight_seat_availability'; // must match view name
    protected $primaryKey = 'flight_id'; // since the view uses flight_id as unique key
    public $incrementing = false; // because it's a view
    public $timestamps = false;   // no created_at / updated_at columns

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
