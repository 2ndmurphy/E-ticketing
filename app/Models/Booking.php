<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'flight_id',
        'number_of_seats',
        'total_price',
        'payment_status',
        'booking_status',
        'booking_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassanger::class);
    }

    public function histories()
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }
}
