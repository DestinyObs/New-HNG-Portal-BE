<?php
namespace App\Services;
use App\Models\Application;
use App\Models\JobListing;
use App\Models\Company;
class ApplicationService {
    public function getApplicatonsForJob(string $companyUuid, string $jobId){
        $company = Company::where('uuid', $companyUuid)->first();
        $job = JobListing::where('id', $jobId)
            ->where('company_id', $company->id)
            ->first();

        if(!$job){
            return null;
        }

        return Application::with('user')
        ->where('job_id', $job->id)
        ->get();
    }
}
