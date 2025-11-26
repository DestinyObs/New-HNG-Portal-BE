<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SkillFactory extends Factory
{
    public function definition(): array
    {
        // Generate a random skill name with random number to avoid uniqueness issues
        $name = fake()->word().' '.fake()->randomNumber(3);

        return [
            'slug' => Str::slug($name),
            'name' => $name,
        ];
    }
}
