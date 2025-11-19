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

    /**
     * @OA\Get(
     *     path="/api/jobs",
     *     summary="List all jobs with filters",
     *     tags={"Jobs"},
     *
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Search by job title or description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="location_id",
     *         in="query",
     *         required=false,
     *         description="Filter by location ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         required=false,
     *         description="Filter by location name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="job_type_id",
     *         in="query",
     *         required=false,
     *         description="Filter by job type ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="track_id",
     *         in="query",
     *         required=false,
     *         description="Filter by track ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         required=false,
     *         description="Filter by category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         required=false,
     *         description="Minimum price filter",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         required=false,
     *         description="Maximum price filter",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved list of jobs"
     *     )
     * )
     */



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
    /**
     * @OA\Get(
     *     path="/api/jobs/{job}",
     *     summary="Get a single job by ID",
     *     tags={"Jobs"},
     *
     *     @OA\Parameter(
     *         name="job",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Job details retrieved"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found"
     *     )
     * )
     */

    public function show(JobListing $job)
    {
        $job = $this->jobService->find($job);

        return $this->successWithData($job, 'Job retrieved successfully');
    }

    /**
     * GET /jobs/{job}/related
     * Related job suggestions
     */

    /**
     * @OA\Get(
     *     path="/api/jobs/{job}/related",
     *     summary="Get related jobs",
     *     tags={"Jobs"},
     *
     *     @OA\Parameter(
     *         name="job",
     *         in="path",
     *         required=true,
     *         description="Job ID to fetch related jobs for",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Related jobs retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found"
     *     )
     * )
     */

    public function related(JobListing $job)
    {
        $related = $this->jobService->related($job);

        return $this->successWithData($related, 'Related jobs retrieved');
    }

    /**
     * GET /jobs/search?q=...
     * Search jobs
     */

    /**
     * @OA\Get(
     *     path="/api/jobs/search",
     *     summary="Search jobs using keyword",
     *     tags={"Jobs"},
     *
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Search keyword",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Search results returned"
     *     )
     * )
     */

    public function search(Request $request)
    {
        $query = $request->query('q', '');

        $results = $this->jobService->search($query);

        return $this->successWithData($results, 'Search results retrieved');
    }
}
