<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;

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
            Track::factory()->create($track);
        }
    }
}

