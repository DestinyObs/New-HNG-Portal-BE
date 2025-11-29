<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobTypeSeeder extends Seeder
{
    public function run(): void
    {
        $jobTypes = [
            ['name' => 'Full-time'],
            ['name' => 'Part-time'],
            ['name' => 'Contract'],
            ['name' => 'Freelance'],
            ['name' => 'Internship'],
            ['name' => 'Temporary'],
        ];

        foreach ($jobTypes as $jobType) {
            JobType::create([
                'name' => $jobType['name'],
                'slug' => Str::slug($jobType['name']),
            ]);
        }
    }
}