<?php

namespace App\Services;

use App\Models\JobListing;
use App\Repositories\JobRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

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

    public function create(string $companyUuid, array $data): JobListing
    {
        // Basic business validations beyond request validation can go here
        return $this->repo->createForCompany($companyUuid, $data);
    }

    public function updateForCompany(string $companyUuid, string $jobId, array $data): ?JobListing
    {
        $job = $this->getForCompany($companyUuid, $jobId);
        if (!$job) {
            return null;
        }

        return $this->repo->update($job, $data);
    }

    public function deleteForCompany(string $companyUuid, string $jobId): bool
    {
        $job = $this->getForCompany($companyUuid, $jobId);
        if (!$job) {
            return false;
        }
        $this->repo->softDelete($job);
        return true;
    }

    public function restore(string $jobId): ?JobListing
    {
        return $this->repo->restore($jobId);
    }
}
