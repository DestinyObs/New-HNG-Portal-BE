<?php

namespace App\Repositories\Employer;

use App\Models\Company;
use App\Models\JobListing;
use App\Models\Application;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository
{
    public function __construct() {}

    /**Get applicants for a specific job with optional filters*/
    public function getJobApplicants(string $companyUuid, string $jobUuid, array $filters = []): LengthAwarePaginator
    {
        // Company UUID stored in 'id'
        $company = Company::findOrFail($companyUuid);

        // Job UUID stored in 'id'
        $job = JobListing::where('id', $jobUuid)
            ->where('company_id', $company->id)
            ->firstOrFail();

        // Query applications
        $query = Application::with(['candidate', 'candidate.skills', 'candidate.experiences'])
            ->where('job_id', $job->id);

        // Optional filters
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

    /**Verify that the job belongs to the company*/
    public function verifyCompanyJobOwnership(string $companyUuid, string $jobUuid): array
    {
        $company = Company::findOrFail($companyUuid);

        $job = JobListing::where('id', $jobUuid)
            ->where('company_id', $company->id)
            ->firstOrFail();

        return compact('company', 'job');
    }
}
