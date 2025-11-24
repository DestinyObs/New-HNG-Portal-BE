<?php

namespace Tests\Feature\Employer;

use App\Models\Company;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_list_jobs()
    {
        $company = Company::factory()->create();
        JobListing::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        // Create a new user using a factory
        $user = User::factory()->create();
        // Authenticate the user and access the route
        $response = $this->actingAs($user)->getJson("api/employer/company/{$company->id}/jobs");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'description', 'company_id'],
                ],
            ]);
    }

    // FROM ABDULSALAM AMTECH The acceptance criteria field is required. (and 8 more errors)
    // public function test_employer_can_create_a_job()
    // {

    //     // Create a new user using a factory
    //     $user = User::factory()->create();
    //     $company = Company::factory()->create([
    //         'user_id' => $user->id
    //     ]);

    //     $payload = [
    //         'title' => 'Backend Developer',
    //         'description' => 'Write APIs',
    //     ];
    //     // Authenticate the user and access the route
    //     $response = $this->actingAs($user)->postJson("/api/employer/company/{$company->id}/jobs/store", $payload);

    //     $response->assertStatus(201)
    //         ->assertJsonFragment($payload);
    // }

    // public function test_employer_can_update_job()
    // {
    //     // Create a new user using a factory
    //     $user = User::factory()->create();
    //     $company = Company::factory()->create([
    //         'user_id' => $user->id
    //     ]);
    //     $job = JobListing::factory()->create([
    //         'company_id' => $company->id
    //     ]);

    //     $updateData = [
    //         'title' => 'Updated Job Title'
    //     ];

    //     // Authenticate the user and access the route
    //     $response = $this->actingAs($user)->putJson(
    //         "/api/employer/company/{$company->id}/jobs/{$job->id}",
    //         $updateData
    //     );

    //     $response->assertStatus(200)
    //         ->assertJsonFragment($updateData);
    // }

    public function test_employer_can_view_job_details()
    {
        $company = Company::factory()->create();
        $job = JobListing::factory()->create([
            'company_id' => $company->id,
        ]);

        // Create a new user using a factory
        $user = User::factory()->create();
        // Authenticate the user and access the route
        $response = $this->actingAs($user)->getJson("api/employer/company/{$company->id}/jobs/{$job->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $job->id]);
    }

    public function test_employer_can_soft_delete_job()
    {
        $company = Company::factory()->create();
        $job = JobListing::factory()->create([
            'company_id' => $company->id,
        ]);

        // Create a new user using a factory
        $user = User::factory()->create();
        // Authenticate the user and access the route
        $response = $this->actingAs($user)->deleteJson("api/employer/company/{$company->id}/jobs/{$job->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('job_listings', ['id' => $job->id]);
    }

    public function test_employer_can_restore_job()
    {

        $company = Company::factory()->create();
        $job = JobListing::factory()->create([
            'company_id' => $company->id,
            'deleted_at' => now(),
        ]);

        // Create a new user using a factory
        $user = User::factory()->create();
        // Authenticate the user and access the route
        $response = $this->actingAs($user)->postJson("api/employer/company/{$company->id}/jobs/{$job->id}/restore");

        $response->assertStatus(200);

        $this->assertDatabaseHas('job_listings', [
            'id' => $job->id,
            'deleted_at' => null,
        ]);
    }

    public function test_authenticated_users_can_access_protected_route()
    {
        // Create a new user using a factory
        $user = User::factory()->create();
        $company = Company::factory()->create([
            'user_id' => $user->id,
        ]);

        // Authenticate the user and access the route
        $response = $this->actingAs($user)->get('/api/user');

        // Assert a successful response for authenticated users
        $response->assertStatus(200);
    }
}
