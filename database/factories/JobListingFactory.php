<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobListingFactory extends Factory
{
    public function definition(): array
    {
        $jobTitles = [
            'Senior Frontend Developer',
            'Backend Engineer',
            'Full Stack Developer',
            'DevOps Engineer',
            'Mobile Developer',
            'UI/UX Designer',
            'Product Manager',
            'Data Scientist',
            'Machine Learning Engineer',
            'QA Engineer',
            'Software Architect',
            'Cloud Engineer',
            'Security Engineer',
            'Database Administrator',
            'Technical Lead',
        ];

        return [
            'title' => fake()->randomElement($jobTitles),
            'description' => fake()->paragraphs(3, true),
            'acceptance_criteria' => fake()->optional(0.8)->text(200),
            'candidate_location_id' => null, // Will be set by seeder
            'company_id' => null, // Will be set by seeder
            'price' => fake()->randomFloat(2, 50000, 200000),
            'track_id' => null, // Will be set by seeder
            'category_id' => null, // Will be set by seeder
            'job_type_id' => null, // Will be set by seeder
        ];
    }

    public function withHighSalary(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 150000, 300000),
        ]);
    }

    public function withLowSalary(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 30000, 80000),
        ]);
    }
}

