<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $datas = [
            'total_user' => User::count(),
            'total_airlines' => Airline::count(),
            'total_flights' => Flight::count(),
            'total_bookings' => Booking::count()
        ];

        return view('pages.admin.dashboard', compact('datas'));
    }
}
