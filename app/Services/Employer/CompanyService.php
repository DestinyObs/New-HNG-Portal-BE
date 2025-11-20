<?php

namespace App\Services\Employer;

use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Employer\CompanyRepository;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    /** Get applicants for a specific job with optional filters */
    public function getJobApplicants(string $companyUuid, string $jobUuid, array $filters = []): LengthAwarePaginator
    {
        return $this->companyRepository->getJobApplicants($companyUuid, $jobUuid, $filters);
    }
      public function getApplication(string $companyUuid, string $applicationUuid): Application
    {
        return $this->companyRepository->getApplication($companyUuid, $applicationUuid);
    }
     public function updateApplicationStatus(string $applicationUuid, string $status): Application
    {
        return $this->companyRepository->updateApplicationStatus($applicationUuid, $status);
    }
    /** Verify that the job belongs to the company */
    public function verifyCompanyJobOwnership(string $companyUuid, string $jobUuid): array
    {
        return $this->companyRepository->verifyCompanyJobOwnership($companyUuid, $jobUuid);
    }

    /** Search talents scoped to a specific company with filters */
    public function searchTalents(string $companyId, array $filters = []): LengthAwarePaginator
    {
        return $this->companyRepository->searchTalents($companyId, $filters);
    }

    /** Verify that a specific application belongs to the company */
    public function verifyCompanyApplicationOwnership(string $companyUuid, string $applicationUuid): array
    {
        return $this->companyRepository->verifyCompanyApplicationOwnership($companyUuid, $applicationUuid);
    }
}
