<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Talent\JobResource;
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
            $request->only(['category', 'job_level', 'job_type', 'work_mode', 'sort_by']),
            (int) $request->query('per_page', 15)
        );

        // dd($result);
        if ($response->success) {
            $jobs = JobResource::collection($response->jobs);

            return $this->paginated(
                $jobs,
                $response->message,
                $response->status,
            );
        }

        return $this->error($response->message, $response->status);
    }
}