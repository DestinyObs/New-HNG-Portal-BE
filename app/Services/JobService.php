<?php

namespace App\Services;

use App\Enums\Http;
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
        $filters = [
            'title' => $params['title'] ?? null,
            'job_type_id' => $params['job_type_id'] ?? null,
            'category_id' => $params['category_id'] ?? null,
        ];

        return $this->repo->listForCompany($companyUuid, $perPage, $filters);
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
            logger()->error('Unable to add job: '.$e->getMessage());

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
                ]),
            ];

            // ? return job model
            return $createdJob;
            // return
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Unable to save job: '.$e->getMessage());

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
            logger()->error('Unable to save job to update job: '.$e->getMessage());

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

    public function updateStatus(string $companyUuid, string $jobId, bool $isActive)
    {
        // dd($companyUuid, $jobId);
        $isActive = $isActive ? 'active' : 'in-active';

        $job = $this->getForCompany($companyUuid, $jobId);
        // dd($job);/
        if (! $job) {
            return null;
        }

        return $this->repo->updateStatus($job, $isActive);
    }
}
