<?php

namespace App\Repositories\Employer;

use App\Models\User;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository implements \App\Repositories\Interfaces\CompanyRepositoryInterface
{
    public function __construct() {}

    /** Find company by ID */
    public function findCompanyById(string $companyId): Company
    {
        return Company::findOrFail($companyId);
    }

    /** Get applicants for a specific job with optional filters */
    public function getJobApplicants(string $companyUuid, string $jobUuid, array $filters = []): LengthAwarePaginator
    {
        $company = Company::findOrFail($companyUuid);

        $job = JobListing::where('id', $jobUuid)
            ->where('company_id', $company->id)
            ->firstOrFail();

        $query = Application::with(['candidate', 'candidate.skills', 'candidate.experiences'])
            ->where('job_id', $job->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->whereHas('candidate', function ($q) use ($filters) {
                $q->where('first_name', 'like', "%{$filters['search']}%")
                  ->orWhere('last_name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /** Search talents scoped to a specific company with filters */
    public function searchTalents(string $companyId, array $filters = []): LengthAwarePaginator
    {
        $query = User::with(['skills', 'track', 'candidateLocation', 'verification'])
            ->whereHas('roles', fn($q) => $q->where('name', 'candidate'))
            ->whereHas('applications.job', fn($q) => $q->where('company_id', $companyId));

        if (!empty($filters['skills'])) {
            $query->whereHas('skills', fn($q) => $q->whereIn('skills.id', $filters['skills']));
        }

        if (!empty($filters['track_id'])) {
            $query->where('track_id', $filters['track_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['min_salary'])) {
            $query->where('min_salary', '>=', $filters['min_salary']);
        }

        if (!empty($filters['max_salary'])) {
            $query->where('max_salary', '<=', $filters['max_salary']);
        }

        if (isset($filters['is_verified'])) {
            $query->where('is_verified', $filters['is_verified']);
        }

        if (!empty($filters['search'])) {
            $query->where(fn($q) =>
                $q->where('first_name', 'like', "%{$filters['search']}%")
                  ->orWhere('last_name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
            );
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /** Verify that the job belongs to the company */
    public function verifyCompanyJobOwnership(string $companyUuid, string $jobUuid): array
    {
        $company = Company::findOrFail($companyUuid);

        $job = JobListing::where('id', $jobUuid)
            ->where('company_id', $company->id)
            ->firstOrFail();

        return compact('company', 'job');
    }

    /** Verify that a specific application belongs to the company */
    public function verifyCompanyApplicationOwnership(string $companyUuid, string $applicationUuid): array
    {
        $company = Company::findOrFail($companyUuid);

        $application = Application::where('id', $applicationUuid)
            ->whereHas('job', fn($q) => $q->where('company_id', $company->id))
            ->firstOrFail();

        return compact('company', 'application');
    }
}
