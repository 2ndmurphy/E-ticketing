<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airports = [
            ['code' => 'CGK', 'name' => 'JakPort', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['code' => 'DPS', 'name' => 'BaPort', 'city' => 'Bali', 'country' => 'Indonesia'],
            ['code' => 'SUB', 'name' => 'SurPort', 'city' => 'Surabaya', 'country' => 'Indonesia'],
            ['code' => 'KNO', 'name' => 'MePort', 'city' => 'Medan', 'country' => 'Indonesia'],
        ];

        foreach ($airports as $a) {
            Airport::create($a);
        }
    }
}
