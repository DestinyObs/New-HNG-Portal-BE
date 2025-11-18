<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicationsTest extends TestCase
{
    use RefreshDatabase;

    protected $company;
    protected $job;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();
        $this->job = JobListing::factory()->create([
            'company_id' => $this->company->id // Adjust based on your JobListing structure
        ]);
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_get_applications_for_a_job()
    {
        // Create applications for the job
        $applications = Application::factory()->count(3)->create([
            'job_id' => $this->job->id
        ]);

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'applications' => [
                        '*' => [
                            'id', // string UUID
                            'user_id', // string UUID
                            'job_id', // string UUID
                            'status',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                            'user' => [
                                'id',
                                'name',
                                'email',
                                'email_verified_at',
                                'created_at',
                                'updated_at'
                            ]
                        ]
                    ]
                ])
                ->assertJsonCount(3, 'applications');
    }

    /** @test */
    public function it_returns_empty_list_when_no_applications_exist()
    {
        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJson(['applications' => []]);
    }

    /** @test */
    public function it_returns_404_when_job_not_found_for_company()
    {
        $otherCompany = Company::factory()->create();
        $otherJob = JobListing::factory()->create([
            'company_id' => $otherCompany->id
        ]);

        // Try to access other company's job through this company's UUID
        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$otherJob->id}/applications");

        $response->assertStatus(404)
                ->assertJson(['message' => 'Job not found for this company']);
    }

    /** @test */
    public function it_returns_404_for_invalid_job_id()
    {
        $invalidJobId = 'non-existent-job-id';

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$invalidJobId}/applications");

        $response->assertStatus(404)
                ->assertJson(['message' => 'Job not found for this company']);
    }

    /** @test */
    public function it_returns_404_for_invalid_company_uuid()
    {
        $invalidUuid = 'invalid-uuid-123';

        $response = $this->getJson("/company/{$invalidUuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_applications_with_user_relationship()
    {
        $application = Application::factory()->create([
            'job_id' => $this->job->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'job_id' => $this->job->id,
                    'user_id' => $this->user->id,
                    'status' => $application->status,
                ])
                ->assertJsonStructure([
                    'applications' => [
                        '*' => [
                            'id',
                            'user_id',
                            'job_id',
                            'status',
                            'user' => [
                                'id',
                                'name',
                                'email'
                            ]
                        ]
                    ]
                ]);

        // Verify UUID format for application ID
        $applicationData = $response->json('applications.0');
        $this->assertTrue(\Illuminate\Support\Str::isUuid($applicationData['id']));
    }

    /** @test */
    public function it_only_returns_applications_for_specific_job()
    {
        // Create applications for the target job
        Application::factory()->count(2)->create([
            'job_id' => $this->job->id
        ]);

        // Create applications for a different job
        $otherJob = JobListing::factory()->create([
            'company_id' => $this->company->id
        ]);
        Application::factory()->count(3)->create([
            'job_id' => $otherJob->id
        ]);

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJsonCount(2, 'applications');

        // Verify only applications for the specific job are returned
        $responseApplications = $response->json('applications');
        $returnedJobIds = collect($responseApplications)->pluck('job_id')->unique();

        $this->assertCount(1, $returnedJobIds);
        $this->assertEquals($this->job->id, $returnedJobIds->first());
    }

    /** @test */
    public function it_handles_different_application_statuses()
    {
        // Create applications with different statuses
        Application::factory()->create([
            'job_id' => $this->job->id,
            'status' => 'pending'
        ]);
        Application::factory()->create([
            'job_id' => $this->job->id,
            'status' => 'accepted'
        ]);
        Application::factory()->create([
            'job_id' => $this->job->id,
            'status' => 'rejected'
        ]);

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJsonCount(3, 'applications');

        // Verify all statuses are included
        $responseApplications = $response->json('applications');
        $statuses = collect($responseApplications)->pluck('status')->toArray();

        $this->assertContains('pending', $statuses);
        $this->assertContains('accepted', $statuses);
        $this->assertContains('rejected', $statuses);
    }

    /** @test */
    public function it_returns_correct_application_data_with_user_details()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $application = Application::factory()->create([
            'job_id' => $this->job->id,
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'job_id' => $this->job->id,
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'user' => [
                        'id' => $user->id,
                        'name' => 'John Doe',
                        'email' => 'john@example.com'
                    ]
                ]);

        // Verify UUID format
        $applicationData = $response->json('applications.0');
        $this->assertTrue(\Illuminate\Support\Str::isUuid($applicationData['id']));
    }

    /** @test */
    public function it_excludes_soft_deleted_applications()
    {
        // Create active applications
        $activeApplications = Application::factory()->count(2)->create([
            'job_id' => $this->job->id
        ]);

        // Create soft deleted application
        $deletedApplication = Application::factory()->create([
            'job_id' => $this->job->id
        ]);
        $deletedApplication->delete();

        $response = $this->getJson("/company/{$this->company->uuid}/jobs/{$this->job->id}/applications");

        $response->assertStatus(200)
                ->assertJsonCount(2, 'applications');

        // Verify soft deleted application is not included
        $responseApplications = $response->json('applications');
        $applicationIds = collect($responseApplications)->pluck('id')->toArray();

        $this->assertNotContains($deletedApplication->id, $applicationIds);
    }
}
