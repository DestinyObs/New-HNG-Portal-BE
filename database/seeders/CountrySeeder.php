<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Nigeria'],
            ['name' => 'Ghana'],
            ['name' => 'Kenya'],
            ['name' => 'South Africa'],
            ['name' => 'United States'],
            ['name' => 'United Kingdom'],
            ['name' => 'Canada'],
            ['name' => 'Germany'],
            ['name' => 'France'],
            ['name' => 'Australia'],
        ];

        foreach ($countries as $country) {
            Country::factory()->create(
                [
                    'name' => $country['name'],
                ]
            );
        }
    }
}
