<?php

namespace App\Repositories\Interfaces\Talent;

use App\Models\Application;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ApplicationRepositoryInterface
{
    public function store(array $data): Application|EloquentCollection;
    public function show(string $applicationId): Application|EloquentCollection;
    public function getAll(): Application|EloquentCollection;
    public function withdraw(string $applicationId): Application|EloquentCollection;
    public function checkIfJobCanBeApplied(string $jobId): bool;
}