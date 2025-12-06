<?php

namespace App\Http\Controllers\Airline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.airline.dashboard');
    }
}
