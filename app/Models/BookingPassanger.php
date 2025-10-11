<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPassanger extends Model
{
    protected $fillable = [
        'booking_id',
        'name',
        'email',
        'seat_number'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
