<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');

            return;
        }

        // Create 10 companies using factory
        Company::factory()
            ->count(10)
            ->create([
                'user_id' => fn () => $users->random()->id,
            ]);
    }
}
