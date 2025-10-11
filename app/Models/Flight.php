<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'airline_id',
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departur_time',
        'arrival_time',
        'price',
        'total_seats',
        'status'
    ];

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function departurAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function seatAvailability()
    {
        return $this->hasOne(FlightSeatAvailability::class, 'flight_id');
    }

    public function getAvailableSeatsAttribute()
    {
        return optional($this->seatAvailability)->available_seats ?? $this->total_seats;
    }

}
