<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Lookup tables first (no dependencies)
            CountrySeeder::class,
            StateSeeder::class,
            TagSeeder::class,
            TrackSeeder::class,
            LocationSeeder::class,
            CategorySeeder::class,
            JobTypeSeeder::class,
            SkillSeeder::class,
            WorkModeSeeder::class,
            MediaAssetSeeder::class,
            JobLevelSeeder::class,

            // Users (depends on nothing initially)
            UserSeeder::class,

            // Addresses (depends on users, countries, states)
            AddressSeeder::class,

            // Companies (depends on users)
            CompanySeeder::class,

            // Job Listings (depends on companies, locations, tracks, categories, job_types, work_modes)
            JobListingSeeder::class,

            // User-related data (depends on users, skills, media)
            UserBioSeeder::class,
            UserSkillSeeder::class,
            TalentWorkExperienceSeeder::class,

            // Other seeders
            FaqSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}