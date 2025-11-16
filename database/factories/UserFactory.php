<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => $this->faker->randomElement(['talent', 'employer', 'admin']),
            'phone' => $this->faker->phoneNumber(),
            'dob' => $this->faker->date('Y-m-d', '2005-01-01'),
            'location' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'status' => 'active',
            'is_verified' => $this->faker->boolean(),
            'bio' => $this->faker->paragraph(),
            'photo_url' => $this->faker->imageUrl(),
            'min_salary' => 100000,
            'max_salary' => 500000,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password
            'remember_token' => Str::random(10),
        ];
    }
}
