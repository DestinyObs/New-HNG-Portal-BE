<?php

namespace App\Services;

use App\Enums\Http;
use App\Enums\Status;
use App\Models\JobListing;
use App\Repositories\JobRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class JobService
{
    protected JobRepository $repo;

    public function __construct(JobRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listForCompany(string $companyUuid, array $params = [], int $perPage = 15): LengthAwarePaginator
    {
        // ? check if company id exist and user owns a company
        if (! $this->repo->checkIfCompanyIdExist($companyUuid)) {
            throw new \Exception('You can only create a job for a company linked to your account.');
        }

        $filters = [
            'title' => $params['title'] ?? null,
            'job_type_id' => $params['job_type_id'] ?? null,
            'category_id' => $params['category_id'] ?? null,
            'track_id' => $params['track_id'] ?? null,
            'sort_by' => $params['sort_by'] ?? null,
        ];

        return $this->repo->listForCompany($companyUuid, $perPage, $filters);
    }


    public function listActiveJobs(string $companyUuid, array $params = [], int $perPage = 15)
    {
        $filters = [
            'title' => $params['title'] ?? null,
            'job_type_id' => $params['job_type_id'] ?? null,
            'category_id' => $params['category_id'] ?? null,
            'track_id' => $params['track_id'] ?? null,
            'sort_by' => $params['sort_by'] ?? null,
        ];

        return $this->repo->listActiveJobs($companyUuid, $perPage, $filters);
    }


    public function listDraftedJobs(
        string $companyUuid,
        array $params = [],
        int $perPage = 15
    ): object|array {
        $filters = [
            'title' => $params['title'] ?? null,
            'job_type_id' => $params['job_type_id'] ?? null,
            'category_id' => $params['category_id'] ?? null,
            'track_id' => $params['track_id'] ?? null,
        ];

        try {
            $listedDrafts = $this->repo->listDraftedJobs(
                $companyUuid,
                $perPage,
                $filters
            );

            return (object) [
                'success' => true,
                'data' => $listedDrafts,
                'message' => 'Drafted jobs retrive successfully',
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            logger()->error('Failed to retrieve drafted jobs: ' . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unabled to retrieve drafted jobs',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function getForCompany(string $companyUuid, string $jobId): ?JobListing
    {
        return $this->repo->findByIdForCompany($companyUuid, $jobId);
    }

    public function create(string $companyUuid, array $data): array|object
    {
        // ? check if company id exist and user owns a company
        if (! $this->repo->checkIfCompanyIdExist($companyUuid)) {
            return (object) [
                'success' => false,
                'message' => 'You can only create a job for a company linked to your account.',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }

        try {
            DB::beginTransaction();
            // ? create a new job
            $createdJob = $this->repo->createForCompany($companyUuid, $data);

            // ? store job and skill relationship
            $skills = $data['skills'];
            $this->repo->addJobSkills($skills, $createdJob->id);

            DB::commit();

            return (object) [
                'success' => true,
                'message' => 'Job created successfully',
                'status' => Http::OK,
                'data' => $createdJob->load('skills'),
            ];

            // ? return job model
            return $createdJob;
            // return
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Unable to add job: ' . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unable to create a new job',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function createOrUpdate(
        string $companyUuid,
        array $data,
        bool $isDraft,
        bool $isPublish = false
    ): array|object {
        // ? check if company id exist and user owns a company
        if (! $this->repo->checkIfCompanyIdExist($companyUuid)) {
            return (object) [
                'success' => false,
                'message' => 'You can only create a job for a company linked to your account.',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }

        try {
            DB::beginTransaction();
            // ? create a new job
            $updatedDraft = $this->repo->createOrUpdateJob(
                $companyUuid,
                $data,
                $isDraft,
                $isPublish
            );

            // ? store job and skill relationship
            $skills = $data['skills'];
            $this->repo->addJobSkills($skills, $updatedDraft->id);

            DB::commit();

            return (object) [
                'success' => true,
                'message' => 'Job added to successfully',
                'status' => Http::OK,
                'data' => $updatedDraft->load([
                    'category',
                    'states',
                    'countries',
                    'category',
                    'jobType',
                    'track',
                    'skills',
                    'jobLevels'
                ]),
            ];

            // ? return job model
            return $createdJob;
            // return
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Unable to save job: ' . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Unable to save job',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function updateForCompany(string $companyUuid, string $jobId, array $data): JobListing|bool
    {
        $job = $this->getForCompany($companyUuid, $jobId);
        if (! $job) {
            return false;
        }

        try {
            DB::beginTransaction();

            $updatedJob = $this->repo->update($job, $data);

            // ? store job and skill relationship
            $skills = $data['skills'];
            $this->repo->addJobSkills($skills, $jobId);

            DB::commit();

            // ? return job model
            return $updatedJob;
            // return
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Unable to save job to update job: ' . $e->getMessage());

            return false;
        }
    }

    public function deleteForCompany(string $companyUuid, string $jobId): bool
    {
        $job = $this->getForCompany($companyUuid, $jobId);
        if (! $job) {
            return false;
        }
        $this->repo->softDelete($job);

        return true;
    }


    public function restore(string $jobId): ?JobListing
    {
        return $this->repo->restore($jobId);
    }

    public function publish(string $companyUuid, string $jobId, string $isPublish)
    {
        $isPublish = $isPublish ? 'published' : 'unpublished';

        $job = $this->getForCompany($companyUuid, $jobId);
        if (! $job) {
            return null;
        }

        return $this->repo->publish($job, $isPublish);
    }

    public function getAllApplication(string $companyUuid, string $uuid)
    {
        try {
            if (! $this->repo->checkIfCompanyIdExist($companyUuid)) {
                return (object) [
                    'success' => false,
                    'message' => 'You can only create a job for a company linked to your account.',
                    'status' => Http::INTERNAL_SERVER_ERROR,
                ];
            }

            $applications = $this->repo->getApplications($uuid);

            return (object) [
                'success' => true,
                'message' => 'Applications retrieved successfully',
                'applications' => $applications,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => 'Unable to retrieve applications',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }


    public function updateStatus(string $companyUuid, string $uuid, bool $status)
    {
        try {
            if (! $this->repo->checkIfCompanyIdExist($companyUuid)) {
                return (object) [
                    'success' => false,
                    'message' => 'You can only create a job for a company linked to your account.',
                    'status' => Http::INTERNAL_SERVER_ERROR,
                ];
            }

            $status = $status ? Status::ACTIVE->value : Status::INACTIVE->value;

            $job = $this->getForCompany($companyUuid, $uuid);
            if (! $job) {
                return (object) [
                    'success' => false,
                    'message' => 'You can only create a job for a company linked to your account.',
                    'status' => Http::INTERNAL_SERVER_ERROR,
                ];
            }

            $job = $this->repo->updateStatus($job, $status);

            return (object) [
                'success' => true,
                'message' => 'Job status updates successfully',
                'job' => $job,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => 'Unable to update status',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }


    public function getSingleApplication(string $companyUuid, string $jobId, string $applicationId)
    {
        try {
            $companyJob = $this->getForCompany($companyUuid, $jobId);
            // dd($companyJob);

            if (!$companyJob) {
                return (object) [
                    'success' => false,
                    'message' => 'Company or job do not exist',
                    'status' => Http::NOT_FOUND,
                ];
            }

            $application = $this->repo->getSingleApplication($jobId, $applicationId);
            // dd($application);

            return (object) [
                'success' => true,
                'message' => 'Single application gotten successfully',
                'application' => $application,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => 'Unable to unable to retrieve application',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }


    public function updateApplicationStatus(string $companyUuid, string $jobId, string $applicationId, string $status)
    {
        try {
            $companyJob = $this->getForCompany($companyUuid, $jobId);
            // dd($companyJob);

            if (!$companyJob) {
                return (object) [
                    'success' => false,
                    'message' => 'Company or job do not exist',
                    'status' => Http::NOT_FOUND,
                ];
            }
            $updatedApplication = $this->repo->updateApplicationStatus($applicationId, $status);

            if (!$updatedApplication) {
                return (object) [
                    'success' => false,
                    'message' => 'Application not found',
                    'status' => Http::NOT_FOUND,
                ];
            }

            return (object) [
                'success' => true,
                'message' => "Application status updated to {$status}",
                'application' => $updatedApplication,
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'message' => 'Unable to update application status',
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }
}
