<?php

namespace Tests\Feature;

use App\Models\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_track()
    {
        $name = "New Track Name";

        $response = $this->postJson('/admin/tracks', [
            'name' => $name,
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tracks', [
            'name' => $name,
        ]);
    }

    public function test_admin_can_list_tracks()
    {
        Track::factory()->count(2)->create();

        $response = $this->getJson('/admin/tracks');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_admin_can_update_track()
    {
        $track = Track::factory()->create();
        $newName = "Updated Track Name";

        $response = $this->putJson("/admin/tracks/{$track->id}", [
            'name' => $newName,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('tracks', [
            'id' => $track->id,
            'name' => $newName,
        ]);
    }

    public function test_admin_can_delete_track()
    {
        $track = Track::factory()->create();

        $response = $this->deleteJson("/admin/tracks/{$track->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
        ]);
    }

    public function test_track_name_is_required()
    {
        $response = $this->postJson('/admin/tracks', []);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_track_name_must_be_unique()
    {
        Track::factory()->create(['name' => 'Backend']);

        $response = $this->postJson('/admin/tracks', [
            'name' => 'Backend'
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }
}
