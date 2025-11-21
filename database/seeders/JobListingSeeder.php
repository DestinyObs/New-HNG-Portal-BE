<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\JobListing;
use App\Models\JobType;
use App\Models\Location;
use App\Models\State;
use App\Models\Track;
use App\Models\User;
use App\Models\WorkMode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {

        JobListing::factory()
            ->count(30)
            ->create([
                'company_id'        => fn() => Company::factory(),
                'state_id'          => fn() => State::factory(),
                'country_id'        => fn() => Country::factory(),
                'track_id'          => fn() => Track::factory(),
                'category_id'       => fn() => Category::factory(),
                'job_type_id'       => fn() => JobType::factory(),
                'work_mode_id'      => fn() => WorkMode::factory(),
                'publication_status' => fn() => ['published', 'unpublished'][rand(0, 1)],
                'status'            => fn() => ['active', 'in-active', 'draft'][rand(0, 2)],
            ]);
    }
}