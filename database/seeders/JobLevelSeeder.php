<?php

namespace Database\Seeders;

use App\Models\JobLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class JobLevelSeeder extends Seeder
{

    public function run(): void
    {
        $levels = [
            "Internship",
            "Entry-Level",
            "Junior-Level",
            "Mid-Level",
            "Senior-Level",
            "Lead-Level",
            "Manager-Level",
            "Director-Level",
            "VP-Level",
            "C-Level",
            "Contract / Consultant",
        ];

        foreach ($levels as $level) {
            JobLevel::firstOrCreate(
                ['name' => $level],  // unique column
                [
                    'id' => Str::uuid(),
                    'slug' => Str::slug($level),
                    'description' => $level . " job position",
                ]
            );
        }
    }
}