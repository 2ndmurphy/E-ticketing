<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::with(['manager', 'flights'])->get();

        return view('pages.admin.airlines.index', compact('airlines'));
    }
}
