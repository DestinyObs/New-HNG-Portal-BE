<?php

namespace Database\Seeders;

use App\Models\TalentWorkExperience;
use App\Models\User;
use Illuminate\Database\Seeder;

class TalentWorkExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');

            return;
        }

        // Add 1-4 work experiences per user
        foreach ($users as $user) {
            $numExperiences = rand(1, 4);

            TalentWorkExperience::factory()
                ->count($numExperiences)
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
