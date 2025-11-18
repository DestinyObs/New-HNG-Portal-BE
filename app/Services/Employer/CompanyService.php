<?php

namespace App\Services\Employer;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Employer\CompanyRepository;


class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}
    public function getJobApplicants(string $companyUuid, string $jobUuid, array $filters = []): LengthAwarePaginator
    {
        return $this->companyRepository->getJobApplicants($companyUuid, $jobUuid, $filters);
    }


    public function verifyCompanyJobOwnership(string $companyUuid, string $jobUuid): array
    {
        return $this->companyRepository->verifyCompanyJobOwnership($companyUuid, $jobUuid);
    }
}
