<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use App\Services\Interfaces\JobTypeInterface;

class JobTypeController extends Controller
{
    public function __construct(
        private readonly JobTypeInterface $jobTypeService
    ) {}

    /**
     * Display a listing of job types.
     */
    public function index()
    {
        $jobTypes = $this->jobTypeService->getAll();
        return $this->successWithData($jobTypes, 'Job types retrieved successfully');
    }

    /**
     * Display the specified job type.
     */
    public function show(JobType $jobType)
    {
        return $this->successWithData($jobType, 'Job type retrieved successfully');
    }
}

