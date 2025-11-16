<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserDeviceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null, // Will be set by seeder
            'name' => fake()->randomElement([
                'iPhone 14 Pro',
                'Samsung Galaxy S23',
                'MacBook Pro',
                'iPad Air',
                'Windows Desktop',
                'Chrome on Windows',
                'Safari on Mac',
                'Firefox on Linux',
            ]),
            'last_activity_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}

