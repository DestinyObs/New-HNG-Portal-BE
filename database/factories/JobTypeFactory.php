<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' ' . fake()->randomNumber(3),
            'slug' => Str::slug($this->faker->unique()->word()),
        ];
    }
}