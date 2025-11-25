<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Remote'],
            ['name' => 'On-site'],
            ['name' => 'Hybrid'],
            ['name' => 'Urgent'],
            ['name' => 'Featured'],
            ['name' => 'Entry Level'],
            ['name' => 'Senior Level'],
            ['name' => 'Mid Level'],
            ['name' => 'Freelance'],
            ['name' => 'Contract'],
        ];

        foreach ($tags as $tag) {
            Tag::factory()->create($tag);
        }
    }
}
