<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'othername' => fake()->optional(0.3)->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+234' . fake()->numerify('##########'),
            'dob' => fake()->date('Y-m-d', '-18 years'),
            'status' => fake()->randomElement(['active', 'active', 'active', 'suspended', 'banned']),
            'address_id' => null,
            'photo_url' => fake()->optional(0.4)->imageUrl(200, 200, 'people'),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'banned',
        ]);
    }
}
