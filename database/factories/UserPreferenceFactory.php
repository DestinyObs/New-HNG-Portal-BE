<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserPreferenceFactory extends Factory
{
    public function definition(): array
    {
        $preferences = [
            'theme' => fake()->randomElement(['light', 'dark', 'auto']),
            'notifications' => [
                'email' => fake()->boolean(),
                'push' => fake()->boolean(),
                'sms' => fake()->boolean(),
            ],
            'language' => fake()->randomElement(['en', 'fr', 'es', 'de']),
            'timezone' => fake()->timezone(),
        ];

        $key = fake()->randomElement(array_keys($preferences));

        return [
            'user_id' => null, // Will be set by seeder
            'key' => $key,
            'value' => json_encode($preferences[$key]),
        ];
    }
}
