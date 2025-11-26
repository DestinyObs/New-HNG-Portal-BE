<?php

namespace Database\Factories;

use App\Models\JobLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobLevelFactory extends Factory
{
    protected $model = JobLevel::class;

    public function definition(): array
    {
        return [
            'slug' => Str::slug($this->faker->unique()->word()),
            'name' => $this->faker->word(),
        ];
    }
}