<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Country;
use App\Models\JobLevel;
use App\Models\JobListing;
use App\Models\JobType;
use App\Models\State;
use App\Models\Track;
use App\Models\WorkMode;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {

        JobListing::factory()
            ->count(5)
            ->create([
                'company_id'        => fn() => Company::inRandomOrder()->value('id'),
                'state_id'          => fn() => State::inRandomOrder()->value('id'),
                'country_id'        => fn() => Country::inRandomOrder()->value('id'),
                'track_id'          => fn() => Track::inRandomOrder()->value('id'),
                'category_id'       => fn() => Category::inRandomOrder()->value('id'),
                'job_type_id'       => fn() => JobType::inRandomOrder()->value('id'),
                'work_mode_id'      => fn() => WorkMode::inRandomOrder()->value('id'),
                'job_level_id'      => fn() => JobLevel::inRandomOrder()->value('id'),

                'publication_status' => fn() => ['published', 'unpublished'][rand(0, 1)],
                'status'            => fn() => ['active', 'in-active', 'draft'][rand(0, 2)],
            ]);
    }
}