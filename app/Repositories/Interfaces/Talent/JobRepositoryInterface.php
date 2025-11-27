<?php

namespace App\Repositories\Interfaces\Talent;

use App\Models\Company;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface JobRepositoryInterface
{
    public function getJobs(array $params, int $perPage): LengthAwarePaginator;

    // public function getFilters();

    public function getJob(string $jobUuiD): JobListing|Collection|Null;

    public function toggleSaveJob(string $jobUuiD): JobListing|Collection;

    public function getSavedJobs(array $params, int $perPage): LengthAwarePaginator;

    public function getCompanyDetails(string $companyId): Company;
}
