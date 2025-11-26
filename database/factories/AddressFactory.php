<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'street1' => fake()->streetAddress(),
            'street2' => fake()->optional(0.3)->secondaryAddress(),
            'state_id' => null, // Will be set by seeder
            'country_id' => null, // Will be set by seeder
            'user_id' => null, // Will be set by seeder
            'postal_code' => fake()->postcode(),
        ];
    }
}
