<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Flight extends Model
{
    protected $fillable = [
        'airline_id',
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_time',
        'arrival_time',
        'price',
        'total_seats',
        'status'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function airline()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'flight_id');
    }

    public function getAvailableSeatsAttribute()
    {
        // Hitung total kursi yang sudah dipesan untuk flight ini
        $bookedSeats = $this->bookings()
            ->whereIn('booking_status', ['confirmed', 'pending'])
            ->sum('number_of_seats');

        // Hitung sisa kursi
        return max(0, $this->total_seats - $bookedSeats);
    }

    // Flight Scope Pencarian
    public function scopeSearch(Builder $query, $searchTerm = null)
    {
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('flight_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('status', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('departureAirport', function ($q2) use ($searchTerm) {
                        $q2->where('city', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('arrivalAirport', function ($q3) use ($searchTerm) {
                        $q3->where('city', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%');
                    });
            });
        }
        return $query;
    }
}
