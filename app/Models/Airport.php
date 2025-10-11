<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'code',
        'name',
        'city',
        'country',
    ];

    public function departures() {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivals() {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}
