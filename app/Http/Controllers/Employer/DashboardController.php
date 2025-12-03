<?php

namespace App\Http\Controllers\Employer;

use App\Enums\Http;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Employer\JobListingResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Services\JobService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;
    protected JobService $service;

    public function __construct(JobService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $user = Auth::user();

        $company = Company::where('user_id', $user->id)->first();

        // COUNT ACTIVE JOBS
        $activeJobs = $company->jobs()->where('status', Status::ACTIVE->value)->count();

        // COUNT TOTAL APPLICANTS
        $totalApplicants = $company->applications()->count();
        $totalHires = $company->applications()
            ->where('applications.status', Status::HIRED)
            ->count();

        return $this->successWithData([
            'active_jobs'      => $activeJobs,
            'total_applicants' => $totalApplicants,
            'total_hired' => $totalHires,
        ], 'Dashboard analysis retrieved successfully.');
    }



    public function activeJobs(Request $request, string $companyId)
    {
        $perPage = (int) $request->query('per_page', 15);
        $result = $this->service->listActiveJobs($companyId, $request->only(
            ['title', 'job_type_id', 'category_id', 'track_id', 'sort_by']
        ), $perPage);

        // dd($result);
        $jobs = JobListingResource::collection($result);

        return $this->paginated(
            $jobs,
            "Active jobs retried successfully",
            Http::OK,
        );
    }
}