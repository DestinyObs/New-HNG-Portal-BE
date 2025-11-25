<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserBioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null, // Will be set by seeder
            'content' => fake()->paragraphs(2, true),
            'min_salary' => fake()->numberBetween(50000, 100000),
            'max_salary' => fake()->numberBetween(120000, 250000),
            'track_id' => null, // Will be set by seeder
            'is_verified' => fake()->boolean(60),
            'links' => json_encode([
                'github' => fake()->optional(0.8)->url(),
                'linkedin' => fake()->optional(0.9)->url(),
                'portfolio' => fake()->optional(0.6)->url(),
                'twitter' => fake()->optional(0.5)->url(),
            ]),
            'cv_id' => null, // Will be set by seeder
            'state' => null,
            'country' => null
        ];
    }

    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_verified' => true,
        ]);
    }
}
