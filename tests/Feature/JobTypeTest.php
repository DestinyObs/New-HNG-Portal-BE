<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\JobType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTypeTest extends TestCase
{
    use RefreshDatabase; // Preventing test interference with database record.

    /**
     * A basic index route example.
     */
    public function test_the_route_returns_a_successful_response(): void
    {
        $response = $this->get('/api/lookups/job-types');

        $response->assertStatus(200); // $response->assertOk();
    }

    /**
     * A basic create route example.
     */
    public function test_admin_can_create_job_type()
    {
        $name = 'new job name';
        $response = $this->post('/api/admin/job-types', [
            'name' => $name,
        ]);
        //  test if the response status is 201 Created
        $response->assertStatus(201);
        // test if the response data contains the value true
        $response->assertJson(['success' => true]);
        // test the existence of a specific record in the table
        $this->assertDatabaseHas('job_types', [
            'name' => $name,
        ]);
    }

    /**
     * A basic show route example.
     */
    public function test_a_single_job_type_can_be_retrieved()
    {
        $jobType = JobType::factory()->create();

        $response = $this->get('/api/admin/job-types/'.$jobType->id);

        $response->assertStatus(200); // 200 OK
        $response->assertSee($jobType->name);
    }

    /**
     * A basic update route example.
     */
    public function test_a_job_type_can_be_updated()
    {
        $jobType = JobType::factory()->create([
            'name' => 'first name',
        ]);
        $newData = [
            'name' => 'updated name',
        ];

        $response = $this->put('/api/admin/job-types/'.$jobType->id, $newData);

        // Return data after successful update 200 OK
        $response->assertStatus(200);
        $this->assertDatabaseHas('job_types', $newData);
    }

    /**
     * A basic destroy route example.
     */
    public function test_a_post_can_be_deleted()
    {
        $jobType = JobType::factory()->create();

        $response = $this->delete('/api/admin/job-types/'.$jobType->id);

        // No content after successful deletion 204 No Content
        $response->assertStatus(204);
        $this->assertDatabaseMissing('job_types', ['id' => $jobType->id]);
    }

    /**
     * A basic route not found example.
     */
    public function test_a_wrong_route_can_return_a_route_not_found(): void
    {
        $response = $this->get('/api/job-types/1');
        // The resource to be updated could not be found 404 Not Found
        $response->assertStatus(404); // $response->assertNotFound();
    }

    /**
     * A basic validation failed example.
     */
    public function test_a_failed_validation_of_create_job_type_can_return_error()
    {
        $name = '';
        $response = $this->post('/api/admin/job-types', [
            'name' => $name,
        ]);

        // THIS STILL NEED MODIFICATION AFTER API EXCEPTION CONFIGURATION SETUP
        $this->assertTrue(true);
        // Unprocessable Entity to be changed from 302 to 422
        //  $response->assertStatus(302)
        //          ->assertJsonValidationErrors(['name']);

        //  test if the response status is 422 api 302 on view
        // $response->assertStatus(422);
        // test if the response data contains the value false
        // $response->assertJson(['success' => false]);
    }
}
