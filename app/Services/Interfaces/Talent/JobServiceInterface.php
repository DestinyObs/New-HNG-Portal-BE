<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Talent;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;

interface JobServiceInterface
{
    public function getJobs(array $params, int $perPage): object|array;

    public function filters(): object|array;

    public function getJob(string $jobUuid): object|array;

    public function saveJob(string $jobUuid): object|array;

    public function getAllSavedJobs(array $params, int $perPage): object|array;

    public function dashboardAnalysis(): object|array;

    public function getCompany(string $companyId): object|array;
}