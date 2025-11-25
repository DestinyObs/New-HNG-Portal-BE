<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory(), // Will be set by seeder
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->numberBetween(1000, 999999),
            'description' => fake()->paragraph(3),
            'logo_url' => fake()->optional(0.6)->imageUrl(200, 200, 'business'),
            'country' => fake()->company(),
            'state' => fake()->company(),
            'industry' => $name,
            'website_url' => fake()->optional(0.8)->url(),
            'is_verified' => fake()->boolean(60),
            'official_email' => fake()->companyEmail(),
            'status' => 'active',
        ];
    }

    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_verified' => true,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_verified' => false,
        ]);
    }
}
