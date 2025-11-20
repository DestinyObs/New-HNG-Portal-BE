<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationTest extends TestCase
{

    use RefreshDatabase; // Preventing test interference with database record.

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic index route example.
     */
    public function test_the_route_returns_a_successful_response(): void
    {
        $response = $this->get('/api/admin/locations');

        $response->assertStatus(200); // $response->assertOk();
    }

    /**
     * A basic create route example.
     */
    public function test_admin_can_create_location()
    {
        $name = "new location name";
        $response = $this->post('/api/admin/locations', [
            'name' => $name,
        ]);
        //  test if the response status is 201 Created
        $response->assertStatus(201);
        // test if the response data contains the value true
        $response->assertJson(['success' => true]);
        // test the existence of a specific record in the table
        $this->assertDatabaseHas('locations', [
            'name' => $name,
        ]);
    }

    /**
     * A basic show route example.
     */
    public function test_a_single_location_can_be_retrieved()
    {
        $location = Location::factory()->create();

        $response = $this->get('/api/admin/locations/' . $location->id);

        $response->assertStatus(200); // 200 OK
        $response->assertSee($location->name);
    }


    /**
     * A basic update route example.
     */
    public function test_a_location_can_be_updated()
    {
        $location =  Location::factory()->create([
               'name' => 'location name',
           ]);
        $newData = [
            'name' => 'updated name'
        ];

        $response = $this->put('/api/admin/locations/' . $location->id, $newData);

        // Return data after successful update 200 OK
        $response->assertStatus(200); 
        $this->assertDatabaseHas('locations', $newData);
    }

    /**
     * A basic destroy route example.
     */
    public function test_a_post_can_be_deleted()
    {
        $location = Location::factory()->create();

        $response = $this->delete('/api/admin/locations/' . $location->id);

        // No content after successful deletion 204 No Content
        $response->assertStatus(204); 
        $this->assertDatabaseMissing('locations', ['id' => $location->id]);
    }

    /**
     * A basic route not found example.
     */
    public function test_a_wrong_route_can_return_a_route_not_found(): void
    {
        $response = $this->get('/api/locations/1');
        // The resource to be updated could not be found 404 Not Found
        $response->assertStatus(404); // $response->assertNotFound();
    }

    /**
     * A basic validation failed example.
     */
    public function test_a_failed_validation_of_create_location_can_return_error()
    {   
        $name = "";
        $response = $this->post('/api/admin/locations', [
            'name' => $name,
        ]);

        // THIS STILL NEED MODIFICATION AFTER API EXCEPTION CONFIGURATION SETUP
        $this->assertTrue(true);
        // Unprocessable Entity to be changed from 302 on view to 422 api
         $response->assertStatus(422) 
                 ->assertJsonValidationErrors(['name']);

        // test if the response data contains the value false
        $response->assertJson(['success' => false]);
    }  
}
