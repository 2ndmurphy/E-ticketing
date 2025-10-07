<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin123',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'admin'
            ],
            [
                'name' => 'Maskapai',
                'username' => 'maskapai123',
                'email' => 'maskapai@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'maskapai'
            ],
            [
                'name' => 'User',
                'username' => 'user123',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'user'
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
