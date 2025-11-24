<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            // Nigerian States
            ['name' => 'Lagos'],
            ['name' => 'Abuja'],
            ['name' => 'Kano'],
            ['name' => 'Rivers'],
            ['name' => 'Oyo'],
            ['name' => 'Kaduna'],
            ['name' => 'Enugu'],
            ['name' => 'Delta'],
            ['name' => 'Anambra'],
            ['name' => 'Ogun'],
            // Other countries' states/regions
            ['name' => 'Greater Accra'],
            ['name' => 'Ashanti'],
            ['name' => 'Nairobi'],
            ['name' => 'Mombasa'],
            ['name' => 'Gauteng'],
            ['name' => 'Western Cape'],
            ['name' => 'California'],
            ['name' => 'New York'],
            ['name' => 'Texas'],
            ['name' => 'London'],
            ['name' => 'Ontario'],
            ['name' => 'Bavaria'],
            ['name' => 'Île-de-France'],
            ['name' => 'New South Wales'],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
