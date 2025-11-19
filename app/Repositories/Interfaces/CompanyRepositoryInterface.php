<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    // public function create(array $data);
    public function getJobApplicants(string $companyUuid, string $jobUuid, array $filters = []): LengthAwarePaginator;
}
