<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 media files using factory
        Media::factory()->count(20)->create();
    }
}

