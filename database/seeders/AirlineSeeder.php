<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airline;
use App\Models\User;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airFly = User::where('email', 'admin@airfly.com')->first();
        $agraFlight = User::where('email', 'admin@agra.com')->first();

        Airline::create([
            'name' => 'AirFly Indonesia',
            'code' => 'AFI',
            'country' => 'Indonesia',
            'contact_email' => 'contact@airfly.com',
            'manage_by_user_id' => $airFly->id,
            'is_active' => true,
        ]);

        Airline::create([
            'name' => 'AgraFlight Indonesia',
            'code' => 'AGF',
            'country' => 'Indonesia',
            'contact_email' => 'contact@agra.com',
            'manage_by_user_id' => $agraFlight->id,
            'is_active' => true,
        ]);
    }
}
