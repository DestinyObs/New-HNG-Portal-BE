<?php

namespace Database\Seeders;

use App\Models\WorkMode;
use Illuminate\Database\Seeder;

class WorkModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workModes = [
            ['slug' => 'remote',    'name' => 'Remote'],
            ['slug' => 'onsite',    'name' => 'Onsite'],
            ['slug' => 'hybrid',    'name' => 'Hybrid'],
            ['slug' => 'freelance', 'name' => 'Freelance'],
            ['slug' => 'contract',  'name' => 'Contract'],
        ];

        foreach ($workModes as $mode) {
            WorkMode::factory()->create([
                'slug' => $mode['slug'],
                'name' => $mode['name'],
            ]);
        }
    }
}
