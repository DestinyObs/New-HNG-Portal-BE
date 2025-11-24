<?php

namespace Database\Factories;

use App\Models\WorkMode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WorkModeFactory extends Factory
{
    protected $model = WorkMode::class;

    public function definition(): array
    {
        return [
            'slug' => Str::slug($this->faker->unique()->word()),
            'name' => $this->faker->word(),
        ];
    }
}
