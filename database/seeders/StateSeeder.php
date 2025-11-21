<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['name' => 'Lagos', 'country' => 'Nigeria'],
            ['name' => 'Abuja', 'country' => 'Nigeria'],
            ['name' => 'Kano', 'country' => 'Nigeria'],
            ['name' => 'Rivers', 'country' => 'Nigeria'],
            ['name' => 'Oyo', 'country' => 'Nigeria'],
            ['name' => 'Kaduna', 'country' => 'Nigeria'],
            ['name' => 'Enugu', 'country' => 'Nigeria'],
            ['name' => 'Delta', 'country' => 'Nigeria'],
            ['name' => 'Anambra', 'country' => 'Nigeria'],
            ['name' => 'Ogun', 'country' => 'Nigeria'],
            ['name' => 'Greater Accra', 'country' => 'Ghana'],
            ['name' => 'Ashanti', 'country' => 'Ghana'],
            ['name' => 'Nairobi', 'country' => 'Kenya'],
            ['name' => 'Mombasa', 'country' => 'Kenya'],
            ['name' => 'Gauteng', 'country' => 'South Africa'],
            ['name' => 'Western Cape', 'country' => 'South Africa'],
            ['name' => 'California', 'country' => 'United States'],
            ['name' => 'New York', 'country' => 'United States'],
            ['name' => 'Texas', 'country' => 'United States'],
            ['name' => 'London', 'country' => 'United Kingdom'],
            ['name' => 'Ontario', 'country' => 'Canada'],
            ['name' => 'Bavaria', 'country' => 'Germany'],
            ['name' => 'Île-de-France', 'country' => 'France'],
            ['name' => 'New South Wales', 'country' => 'Australia'],
        ];

        $countries = Country::query()
            ->whereIn('name', collect($states)->pluck('country')->unique())
            ->get()
            ->keyBy('name');

        foreach ($states as $state) {
            $country = $countries->get($state['country']);

            if (! $country) {
                continue;
            }

            State::query()->updateOrCreate(
                ['name' => $state['name']],
                ['country_id' => $country->id]
            );
        }
    }
}

