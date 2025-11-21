<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Lagos, Nigeria'],
            ['name' => 'Abuja, Nigeria'],
            ['name' => 'Accra, Ghana'],
            ['name' => 'Nairobi, Kenya'],
            ['name' => 'Cape Town, South Africa'],
            ['name' => 'New York, USA'],
            ['name' => 'London, UK'],
            ['name' => 'Toronto, Canada'],
            ['name' => 'Berlin, Germany'],
            ['name' => 'Paris, France'],
            ['name' => 'Sydney, Australia'],
            ['name' => 'Remote'],
        ];

        foreach ($locations as $location) {
            Location::factory()->create($location);
        }
    }
}