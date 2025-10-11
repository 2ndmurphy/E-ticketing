<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillbale = [
        'name',
        'code',
        'country',
        'contact_email',
        'manage_by_user_id',
        'is_active',
    ];

    public function manager() {
        return $this->belongsTo(User::class, 'manage_by_user_id');
    }

    public function flights() {
        return $this->hasMany(Flight::class);
    }
}
