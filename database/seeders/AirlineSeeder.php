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
        $airFlyUser = User::firstOrCreate(
            ['email' => 'admin@airfly.com'],
            ['name' => 'AirFly Admin', 'password' => bcrypt('password')]
        );

        $agraUser = User::firstOrCreate(
            ['email' => 'admin@agra.com'],
            ['name' => 'Agra Admin', 'password' => bcrypt('password')]
        );

        Airline::firstOrCreate(
            ['code' => 'AFI'],
            [
                'name' => 'AirFly Indonesia',
                'country' => 'Indonesia',
                'contact_email' => 'contact@airfly.com',
                'manage_by_user_id' => $airFlyUser->id,
                'is_active' => true,
            ]
        );

        Airline::firstOrCreate(
            ['code' => 'AGF'],
            [
                'name' => 'AgraFlight Indonesia',
                'country' => 'Indonesia',
                'contact_email' => 'contact@agra.com',
                'manage_by_user_id' => $agraUser->id,
                'is_active' => true,
            ]
        );
    }
}
