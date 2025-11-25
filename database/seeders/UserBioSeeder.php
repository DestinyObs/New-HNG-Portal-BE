<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\MediaAsset;
use App\Models\User;
use App\Models\UserBio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBioSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $tracks = DB::table('tracks')->pluck('id');

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // Create bio for 70% of users
        $usersWithBio = $users->random(intval($users->count() * 0.7));

        foreach ($usersWithBio as $user) {
            UserBio::factory()->create([
                'user_id' => $user->id,
                'track_id' => $tracks->isNotEmpty() ? $tracks->random() : null,
                'cv_id' => MediaAsset::factory(),
                'state' => 'lagos',
                'country' => 'nigeria'
            ]);
        }
    }
}
