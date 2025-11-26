<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = [
            ['name' => 'Frontend Development'],
            ['name' => 'Backend Development'],
            ['name' => 'Mobile Development'],
            ['name' => 'DevOps'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Product Management'],
            ['name' => 'Data Science'],
            ['name' => 'Machine Learning'],
            ['name' => 'Quality Assurance'],
            ['name' => 'Cybersecurity'],
            ['name' => 'Cloud Engineering'],
            ['name' => 'Blockchain Development'],
        ];

        foreach ($tracks as $track) {
            $track['slug'] = Str::slug($track['name']);
            Track::factory()->create($track);
        }
    }
}