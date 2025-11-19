<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Services\Interfaces\JobInterface;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use OpenApi\Annotations as OA;

class JobController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly JobInterface $jobService
    ) {}


    public function index(Request $request)
    {
        $filters = $request->all();
        $jobs = $this->jobService->list($filters);

        return $this->successWithData($jobs, 'Jobs retrieved successfully');
    }

    /**
     * GET /jobs/{job}
     * Job detail
     */

    public function show(JobListing $jobListing)
    {
        return$jobListing;
        $job = $this->jobService->find($jobListing);

        return $this->successWithData($jobListing, 'Job retrieved successfully');
    }

    /**
     * GET /jobs/{job}/related
     * Related job suggestions
     */

    public function related(JobListing $jobListing)
    {
        $related = $this->jobService->related($jobListing);

        return $this->successWithData($related, 'Related jobs retrieved');
    }

    /**
     * GET /jobs/search?q=...
     * Search jobs
     */

    public function search(Request $request)
    {
        $query = $request->query('q', '');

        $results = $this->jobService->search($query);

        return $this->successWithData($results, 'Search results retrieved');
    }
}
