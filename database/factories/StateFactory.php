<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->state(),
            'country_id' => Country::factory(),
        ];
    }
}

