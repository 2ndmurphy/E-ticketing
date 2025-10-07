<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaskapaiController extends Controller
{
    public function index() 
    {
        return view('pages.maskapai.dashboard');
    }
}
