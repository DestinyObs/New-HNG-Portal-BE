<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\JobType;
use App\Models\Location;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {

        JobListing::factory()
            ->count(30)
            ->create([
                'company_id' => fn() => Company::factory(),
                'candidate_location_id' => fn() => Location::factory(),
                'track_id' => fn() => Track::factory(),
                'category_id' => fn() => Category::factory(),
                'job_type_id' => fn() => jobType::factory(),
            ]);
    }
}

