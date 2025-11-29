<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// class CompanySeeder extends Seeder
// {
//     public function run(): void
//     {
//         $users = User::all();

//         if ($users->isEmpty()) {
//             $this->command->warn('No users found. Please run UserSeeder first.');

//             return;
//         }

//         // Create 10 companies using factory
//         Company::factory()
//             ->count(10)
//             ->create([
//                 'user_id' => fn() => $users->random()->id,
//             ]);
//     }
// }

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // ==========================================
        // 1. CREATE COMPANY FOR THE EMPLOYER USER ONLY
        // ==========================================

        $employer = User::where('email', 'employer@test.com')->first();

        if ($employer) {

            // Only create if not already created
            if (!Company::where('user_id', $employer->id)->exists()) {

                $companyName = 'Test Employer Company';

                Company::create([
                    'user_id'        => $employer->id,
                    'name'           => $companyName,
                    'slug'           => Str::slug($companyName) . '-' . rand(1000, 9999),
                    'description'    => 'This is a test employer company used for testing.',
                    'logo_url'       => null,
                    'country'        => 'Nigeria',
                    'state'          => 'Lagos',
                    'industry'       => 'Technology',
                    'website_url'    => 'https://test-employer-company.com',
                    'is_verified'    => true,
                    'official_email' => 'company@test.com',
                    'status'         => 'active',
                ]);
            }
        }

        // ==========================================
        // 2. CREATE RANDOM COMPANIES FOR OTHER USERS ONLY
        // ==========================================

        $nonEmployerUsers = User::where('email', '!=', 'employer@test.com')->get();

        Company::factory()
            ->count(3)
            ->create([
                'user_id' => fn() => $nonEmployerUsers->random()->id,
            ]);
    }
}