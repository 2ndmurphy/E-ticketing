<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airFly = Airline::firstOrCreate(['code' => 'AFI'], ['name' => 'AirFly Indonesia']);
        $agraFlight = Airline::firstOrCreate(['code' => 'AGF'], ['name' => 'AgraFlight Indonesia']);

        $cgk = Airport::where('code', 'CGK')->first();
        $dps = Airport::where('code', 'DPS')->first();
        $sub = Airport::where('code', 'SUB')->first();

        $flights = [
            [
                'airline_id' => $airFly->id,
                'flight_number' => 'AF101',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $dps->id,
                'departure_time' => Carbon::now()->addDays(1)->setTime(8, 30),
                'arrival_time' => Carbon::now()->addDays(1)->setTime(11, 0),
                'price' => 120000,
                'total_seats' => 200,
                'status' => 'active',
            ],
            [
                'airline_id' => $airFly->id,
                'flight_number' => 'AF202',
                'departure_airport_id' => $dps->id,
                'arrival_airport_id' => $sub->id,
                'departure_time' => Carbon::now()->addDays(2)->setTime(9, 0),
                'arrival_time' => Carbon::now()->addDays(2)->setTime(10, 30),
                'price' => 95000,
                'total_seats' => 180,
                'status' => 'active',
            ],
            [
                'airline_id' => $agraFlight->id,
                'flight_number' => 'AG101',
                'departure_airport_id' => $sub->id,
                'arrival_airport_id' => $cgk->id,
                'departure_time' => Carbon::now()->addDays(2)->setTime(9, 0),
                'arrival_time' => Carbon::now()->addDays(2)->setTime(10, 30),
                'price' => 95000,
                'total_seats' => 180,
                'status' => 'active',
            ],
        ];

        foreach ($flights as $f) {
            Flight::create($f);
        }
    }
}
