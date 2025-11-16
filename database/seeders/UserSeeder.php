<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'is_verified' => true,
            'password' => bcrypt('password'),
        ]);

        // Create 10 talents
        User::factory(10)->create([
            'role' => 'talent',
        ]);

        // Create 5 employers
        User::factory(5)->create([
            'role' => 'employer',
        ]);
    }
}
