<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(2, true),
            'type' => fake()->randomElement(['company', 'talent']),
        ];
    }

    public function forCompany(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'company',
        ]);
    }

    public function forTalent(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'talent',
        ]);
    }
}

