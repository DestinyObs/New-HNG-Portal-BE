<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Services\Interfaces\JobInterface;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class JobController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly JobInterface $jobService
    ) {}

    public function index(Request $request)
    {
        $jobs = $this->jobService->list($request->all());

        return $this->successWithData($jobs, 'Jobs retrieved successfully');
    }

    public function show(JobListing $job)
    {
        return $this->successWithData($job, 'Job retrieved successfully');
    }

    public function related(JobListing $job)
    {
        $related = $this->jobService->related($job);

        return $this->successWithData($related, 'Related jobs retrieved successfully');
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');

        $results = $this->jobService->search($query);

        return $this->successWithData($results, 'Search results retrieved successfully');
    }
}

