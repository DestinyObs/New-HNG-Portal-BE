<?php

namespace App\Services\Interfaces;

use App\Models\JobListing;

interface JobInterface
{
    public function list(array $filters);
    public function find(JobListing $job): JobListing;
    public function related(JobListing $job): array;
    public function search(string $query);
}

