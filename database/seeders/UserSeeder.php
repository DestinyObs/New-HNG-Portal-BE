<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    // Create 20 users with factories
    public function run(): void
    {
        // --- TALENT TEST USER ---
        $talent = User::factory()->create([
            'firstname'     => 'Test',
            'lastname'      => 'Talent',
            'email'         => 'talent@test.com',
            'current_role'  => RoleEnum::TALENT->value,
            'phone'         => '+2348000000000',
            'password'      => Hash::make('password'),
            'status'        => 'active',
        ]);

        if (!$talent->hasVerifiedEmail()) {
            $talent->markEmailAsVerified();
        }

        // --- EMPLOYER TEST USER ---
        $employer = User::factory()->create([
            'firstname'     => 'Test',
            'lastname'      => 'Employer',
            'email'         => 'employer@test.com',
            'current_role'  => RoleEnum::EMPLOYER->value,
            'phone'         => '+2348111111111',
            'password'      => Hash::make('password'),
            'status'        => 'active',
        ]);

        if (!$employer->hasVerifiedEmail()) {
            $employer->markEmailAsVerified();
        }

        // --- RANDOM USERS ---
        $randomUsers = User::factory()->count(20)->create();

        // Mark all random users as verified too if you want:
        foreach ($randomUsers as $user) {
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        }
    }
}