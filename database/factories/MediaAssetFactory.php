<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MediaAssetFactory extends Factory
{
    public function definition(): array
    {
        $types = ['pdf', 'doc', 'docx', 'jpg', 'png'];
        $type = fake()->randomElement($types);

        return [
            'url' => 'https://example.com/media/'.fake()->uuid().'.'.$type,
        ];
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'url' => 'https://example.com/media/'.fake()->uuid().'.pdf',
        ]);
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'url' => fake()->imageUrl(800, 600, 'documents'),
        ]);
    }
}
