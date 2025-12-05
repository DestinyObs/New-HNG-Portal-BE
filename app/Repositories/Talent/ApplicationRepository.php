<?php

namespace App\Repositories\Talent;

use App\Models\Application;
use App\Models\BookmarkedJob;
use App\Models\JobListing;
use App\Models\User;
use App\Models\WorkMode;
use App\Repositories\Interfaces\Talent\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Repositories\Interfaces\Talent\JobRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationRepository implements ApplicationRepositoryInterface
{

    public function store(array $data): Application|EloquentCollection
    {
        $user = Auth::user();

        // Prevent duplicate applications
        $existing = Application::where('user_id', $user->id)
            ->where('job_id', $data['job_id'])
            ->first();

        if ($existing) {
            return $existing->load(['user', 'job', 'job.company']);
        }

        // Create new application
        $application = Application::query()->create([
            'user_id'        => $user->id,
            'job_id'         => $data['job_id'],
            'cover_letter'   => $data['cover_letter'],
            'portfolio_link' => $data['portfolio_url'] ?? null,
            // 'attachment'     => $data['attachment'] ?? null,
        ]);

        return $application->load(['user', 'job', 'job.company']);
    }


    public function show(string $appId): Application|EloquentCollection
    {
        $user = Auth::user();
        return Application::with(['user', 'job', 'job.company'])
            ->where([
                'id' => $appId,
                'user_id' => $user->id,
            ])
            ->firstOrFail();
    }

    public function getAll(array $params, int $perPage): LengthAwarePaginator
    {
        $applications = Application::query()
            ->with(['user', 'job', 'job.company'])
            ->where('user_id', Auth::user()->id);
        // ->latest()
        // ->get();

        return $this->fileterDatas($applications, $params, $perPage);;
    }

    public function withdraw(string $appId): Application|EloquentCollection
    {
        $application = Application::query()
            ->where('id', $appId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $application->update(['status' => 'withdraw']);

        return $application->load(['user', 'job', 'job.company']);
    }


    public function checkIfJobCanBeApplied(string $jobId): bool
    {
        return JobListing::query()
            ->where('id', $jobId)
            ->where('status', 'active')
            ->where('publication_status', 'published')
            ->exists();
    }


    private function fileterDatas(Builder $query, array $params, int $perPage)
    {
        // dd($params);
        $sortBy = 'desc';

        //? filter by category
        if (!empty($params['category'])) {
            $categories = explode(',', $params['category']);

            $query->whereHas('job.category', function ($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // //? filter by job type
        if (!empty($params['job_type'])) {
            $types = explode(',', $params['job_type']);

            $query->whereHas('job.jobType', function ($q) use ($types) {
                $q->whereIn('slug', $types);
            });
        }

        //? filter by job level

        if (!empty($params['job_level'])) {
            $levels = explode(',', $params['job_level']);

            $query->whereHas('job.jobLevels', function ($q) use ($levels) {
                $q->whereIn('slug', $levels);
            });
        }

        //? filter by work mode
        if (!empty($params['work_mode'])) {
            $modes = explode(',', $params['work_mode']);

            // dd(WorkMode::whereIn('slug', $modes)->get());
            $query->whereHas('job.workModes', function ($q) use ($modes) {
                $q->whereIn('slug', $modes);
            });
        }

        if (!empty($params['sort_by'])) {
            $sort = $params['sort_by'];

            $sortBy = $sort == 'oldest' ? 'asc' : 'desc';
            // dd($sortBy);
        }

        if (!empty($params['search'])) {
            $search = $params['search'];

            $query->whereHas('job', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('created_at', $sortBy)->paginate($perPage);
    }
}