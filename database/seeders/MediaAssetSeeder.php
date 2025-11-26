<?php

namespace Database\Seeders;

use App\Models\MediaAsset;
use Illuminate\Database\Seeder;

class MediaAssetSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 media files using factory
        MediaAsset::factory()->count(20)->create();
    }
}
