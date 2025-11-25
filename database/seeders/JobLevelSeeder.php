<?php

namespace Database\Seeders;

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
            DB::table('job_levels')->insert([
                'id'          => Str::uuid(),
                'name'        => $level,
                'slug'        => Str::slug($level),
                'description' => $level . " job position",
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}