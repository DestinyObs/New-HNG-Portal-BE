<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::factory(),     
            'job_id' => JobListing::factory(), 
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
