<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TalentVerificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null, // Will be set by seeder
            'document_url' => fake()->url(),
            'reasons' => fake()->optional()->sentence(),
            'agreement' => true,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'reasons' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'reasons' => fake()->sentence(),
        ]);
    }
}

