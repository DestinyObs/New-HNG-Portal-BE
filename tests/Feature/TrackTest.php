<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Track;

class TrackTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_track()
    {
        $response = $this->postJson('/api/admin/tracks', [
            'name' => 'Backend Development'
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Track created successfully'
                 ]);
    }

    public function test_can_list_tracks()
    {
        Track::factory()->create(['name' => 'Frontend Development']);

        $response = $this->getJson('/api/admin/tracks');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data'
                 ]);
    }

    public function test_can_update_track()
    {
        $track = Track::factory()->create(['name' => 'Old Track']);

        $response = $this->putJson('/api/admin/tracks/'.$track->id, [
            'name' => 'Updated Track'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Track updated successfully'
                 ]);
    }

    public function test_can_delete_track()
    {
        $track = Track::factory()->create(['name' => 'Delete Me']);

        $response = $this->deleteJson('/api/admin/tracks/'.$track->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Track deleted successfully'
                 ]);
    }
}
