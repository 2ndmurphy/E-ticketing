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
        $maskapaiAdmin = User::where('email', 'admin@airfly.com')->first();

        Airline::create([
            'name' => 'AirFly Indonesia',
            'code' => 'AFI',
            'country' => 'Indonesia',
            'contact_email' => 'contact@airfly.com',
            'manage_by_user_id' => $maskapaiAdmin->id,
            'is_active' => true,
        ]);
    }
}
