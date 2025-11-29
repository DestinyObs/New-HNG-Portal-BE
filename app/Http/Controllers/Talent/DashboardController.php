<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\Talent\JobServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly JobServiceInterface $jobService,
    ) {}
    public function analysis(Request $request)
    {
        $user = Auth::user();

        // total saved jobs
        $savedJobsCount = $user->bookmarks()->count();

        // total applications
        $applicationsCount = $user->applications()->count();

        return $this->successWithData([
            'total_saved_jobs' => $savedJobsCount,
            'total_applications' => $applicationsCount,
        ], 'Dashboard analyis fetched successfully.');
    }

public function recommendedJobs(Request $request)
{
    $response = $this->jobService->getJobs(
        $request->only(['category', 'job_level', 'job_type', 'work_mode']),
        (int) $request->query('per_page', 15)
    );

    return $response->success
        ? $this->successWithData($response->jobs, $response->message, $response->status)
        : $this->error($response->message, $response->status);
}
}
