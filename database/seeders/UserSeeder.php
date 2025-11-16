<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\RoleEnum;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'is_verified' => true,
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole(RoleEnum::ADMIN->value);

        // Create 5 talents
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole(RoleEnum::TALENT->value);
        });

        // Create 3 employers
        User::factory(3)->create()->each(function ($user) {
            $user->assignRole(RoleEnum::EMPLOYER->value);
        });
    }
}
