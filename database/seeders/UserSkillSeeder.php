<?php

namespace Database\Seeders;

use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSkillSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');
        $skills = DB::table('skills')->pluck('id');

        if ($users->isEmpty() || $skills->isEmpty()) {
            $this->command->warn('Missing required data. Please run UserSeeder and SkillSeeder first.');

            return;
        }

        $userSkills = [];

        foreach ($users as $userId) {
            // Give each user 3-7 random skills
            $numSkills = rand(3, 7);
            $selectedSkills = $skills->random($numSkills);

            foreach ($selectedSkills as $skillId) {
                UserSkill::factory()->create([
                    'user_id' => $userId,
                    'skill_id' => $skillId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}
