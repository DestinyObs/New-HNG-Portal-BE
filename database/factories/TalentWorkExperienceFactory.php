<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TalentWorkExperienceFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-5 years', '-6 months');
        $isCurrent = fake()->boolean(30);
        $endDate = $isCurrent ? null : fake()->dateTimeBetween($startDate, 'now');

        $jobTitles = [
            'Software Engineer',
            'Senior Developer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'DevOps Engineer',
            'Mobile Developer',
            'UI/UX Designer',
            'Product Manager',
            'Technical Lead',
            'Software Architect',
            'Data Engineer',
        ];

        return [
            'user_id' => User::factory(), // Will be set by seeder
            'company_name' => fake()->company(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'job_title' => fake()->randomElement($jobTitles),
            'description' => fake()->optional(0.8)->paragraph(),
            'status' => $isCurrent ? 'active' : 'inactive',
        ];
    }

    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => null,
            'status' => 'active',
        ]);
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'end_date' => fake()->dateTimeBetween($attributes['start_date'], 'now'),
            'status' => 'inactive',
        ]);
    }
}

