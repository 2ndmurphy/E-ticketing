<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Airline Admin
        User::create([
            'name' => 'Admin',
            'username' => 'admin123',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Airline Admin',
            'username' => 'admin_maskapai',
            'email' => 'admin@airfly.com',
            'password' => Hash::make('password'),
            'role' => 'maskapai',
        ]);

        // Customer
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe123',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Murphy Lawden',
            'username' => 'murphy123',
            'email' => 'murphy@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
