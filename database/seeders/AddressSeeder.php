<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $states = DB::table('states')->pluck('id');
        $countries = DB::table('countries')->pluck('id');

        if ($users->isEmpty() || $states->isEmpty() || $countries->isEmpty()) {
            $this->command->warn('Missing required data. Please run UserSeeder, StateSeeder, and CountrySeeder first.');

            return;
        }

        // Create an address for each user
        foreach ($users as $user) {
            $address = Address::factory()->create([
                'user_id' => $user->id,
                'state_id' => $states->random(),
                'country_id' => $countries->random(),
            ]);

            // Update user with their address_id
            $user->update(['address_id' => $address->id]);
        }
    }
}
