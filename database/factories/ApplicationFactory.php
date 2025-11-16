<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' ' . fake()->randomElement(['Mobile App', 'Web App', 'Desktop App', 'API']),
        ];
    }
}

