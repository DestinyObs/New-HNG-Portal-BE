<?php

namespace App\Repositories\Talent;

use App\Enums\Status;
use App\Models\BookmarkedJob;
use App\Models\Company;
use App\Models\JobListing;
use App\Models\User;
use App\Models\WorkMode;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Repositories\Interfaces\Talent\JobRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class JobRepository implements JobRepositoryInterface
{
    public function getJobs(array $params, int $perPage): LengthAwarePaginator
    {
        // dd($params);
        //? get all latest job listings
        $query = JobListing::query()
            ->where('status', 'active')
            ->with([
                'category',
                'states',
                'countries',
                'category',
                'jobType',
                'track',
                'skills',
                'jobLevels',
                'company',
            ]);

        return $this->fileterDatas($query, $params, $perPage);
    }

    public function getJob(string $jobUuiD): JobListing|Collection|Null
    {
        return JobListing::query()
            ->where('id', $jobUuiD)
            ->where('status', 'active')
            ->with([
                'category',
                'states',
                'countries',
                'category',
                'jobType',
                'track',
                'skills',
                'jobLevels',
                'workModes',
                'bookmarks',
                'company',
            ])
            ->first();
    }

    public function toggleSaveJob(string $jobUuid): JobListing|Collection
    {
        $user = auth()->user();
        $job = JobListing::with([
            'category',
            'states',
            'countries',
            'jobType',
            'track',
            'skills',
            'jobLevels',
            'workModes',
            'bookmarks',
            'company',
        ])->findOrFail($jobUuid);

        $exists = $user->bookmarks()
            ->where('job_listing_id', $job->id)
            ->exists();

        if ($exists) {
            // unbookmark
            $user->bookmarks()->detach($job->id);

            return collect([
                'message' => 'Job removed from bookmarks',
                'job' => $job
            ]);
        }

        // bookmark
        $user->bookmarks()->attach($job->id);

        return collect([
            'message' => 'Job added to bookmarks',
            'job' => $job
        ]);
    }


    public function getSavedJobs(array $params, int $perPage): LengthAwarePaginator
    {
        $user = auth()->user();

        $query = BookmarkedJob::query()
            ->where('user_id', $user->id)
            ->whereHas('jobListing', function ($q) {
                $q->where('status', 'active');
            })
            ->with([
                'jobListing' => function ($q) {
                    $q->with([
                        'category',
                        'states',
                        'countries',
                        'jobType',
                        'track',
                        'skills',
                        'jobLevels',
                        'workModes',
                        'bookmarks',
                        'company',
                    ]);
                }
            ]);

        // Apply filters and paginate
        $paginated = $this->fileterDatas($query, $params, $perPage);

        // Keep pagination, but return only the jobListings collection for each bookmarked job
        $paginated->getCollection()->transform(function ($bookmarkedJob) {
            return $bookmarkedJob->jobListing;
        });

        return $paginated;
    }



    private function fileterDatas(Builder $query, array $params, int $perPage)
    {
        //? filter by category
        if (!empty($params['category'])) {
            $categories = explode(',', $params['category']);

            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // //? filter by job type
        if (!empty($params['job_type'])) {
            $types = explode(',', $params['job_type']);

            $query->whereHas('jobType', function ($q) use ($types) {
                $q->whereIn('slug', $types);
            });
        }

        //? filter by job level

        if (!empty($params['job_level'])) {
            $levels = explode(',', $params['job_level']);

            $query->whereHas('jobLevels', function ($q) use ($levels) {
                $q->whereIn('slug', $levels);
            });
        }

        //? filter by work mode
        if (!empty($params['work_mode'])) {
            $modes = explode(',', $params['work_mode']);

            // dd(WorkMode::whereIn('slug', $modes)->get());
            $query->whereHas('workModes', function ($q) use ($modes) {
                $q->whereIn('slug', $modes);
            });
        }


        return $query->latest()->paginate($perPage);
    }


    public function getCompanyDetails(string $companyId): Company
    {
        return Company::query()
            ->where('id', $companyId)
            ->where('status', Status::ACTIVE->value)
            ->first();
    }
}
