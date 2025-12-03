<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class JobListingFactory extends Factory
{
    public function definition(): array
    {
        $jobTitles = [
            'Senior Frontend Developer',
            'Backend Engineer',
            'Full Stack Developer',
            'DevOps Engineer',
            'Mobile Developer',
            'UI/UX Designer',
            'Product Manager',
            'Data Scientist',
            'Machine Learning Engineer',
            'QA Engineer',
            'Software Architect',
            'Cloud Engineer',
            'Security Engineer',
            'Database Administrator',
            'Technical Lead',
        ];

        // Fetch all countries
        $countries = Http::get('https://countriesnow.space/api/v0.1/countries/positions')
            ->json()['data'];

        // Get already used countries
        $usedCountries = \App\Models\JobListing::pluck('country')->toArray();

        // Pick a random country
        $country = collect($countries)->random();
        $countryName = $country['name'];

        // Fetch states for this country
        $statesResp = Http::post('https://countriesnow.space/api/v0.1/countries/states', [
            'country' => $countryName
        ])->json()['data']['states'];

        $state = collect($statesResp)->random()['name'] ?? null;

        return [
            'title' => fake()->randomElement($jobTitles),
            'description' => fake()->paragraphs(3, true),
            'acceptance_criteria' => fake()->optional(0.8)->text(200),
            'state' => $state, // Will be set by seeder if needed
            'country' => $countryName, // Will be set by seeder if needed
            'company_id' => null, // Will be set by seeder
            'price' => fake()->randomFloat(2, 50000, 200000),
            'track_id' => null, // Will be set by seeder
            'category_id' => null, // Will be set by seeder
            'job_type_id' => null, // Will be set by seeder
            'work_mode_id' => null, // Will be set by seeder
            'status' => fake()->randomElement(['active', 'in-active', 'draft']),
            'publication_status' => fake()->randomElement(['published', 'unpublished']),
        ];
    }

    public function withHighSalary(): static
    {
        return $this->state(fn(array $attributes) => [
            'price' => fake()->randomFloat(2, 150000, 300000),
        ]);
    }

    public function withLowSalary(): static
    {
        return $this->state(fn(array $attributes) => [
            'price' => fake()->randomFloat(2, 30000, 80000),
        ]);
    }
}