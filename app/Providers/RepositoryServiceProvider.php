<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\CountryRepository;
use App\Repositories\Admin\JobLevelRepository;
// Repositories
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\StateRepository;
// ? Repositories for talent role
use App\Repositories\Admin\TrackRepository;
use App\Repositories\Admin\WorkModeRepository;
// ? Respositories for admin role
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use App\Repositories\Interfaces\Admin\CountryRepositoryInterface;
use App\Repositories\Interfaces\Admin\JobLevelRepositoryInterface;
use App\Repositories\Interfaces\Admin\LocationRepositoryInterface;
use App\Repositories\Interfaces\Admin\StateRepositoryInterface;
use App\Repositories\Interfaces\Admin\TrackRepositoryInterface;
use App\Repositories\Interfaces\Admin\WorkModeRepositoryInterface;
use App\Repositories\Interfaces\Talent\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\Talent\JobRepositoryInterface;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\JobRepository;
use App\Repositories\Talent\ApplicationRepository;
use App\Repositories\Talent\JobRepository as TalentJobRepository;
use App\Repositories\Talent\ProfileRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

// Services for talent role

// ? Services for admin role

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Binds interfaces to their implementations.
     *
     * @var array<string, string>
     */
    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,

        // ? TALANET PROFILE BINDINGS
        ProfileRepositoryInterface::class => ProfileRepository::class,

        // ? ADMIN CATEGORY BINDINGS
        CategoryRepositoryInterface::class => CategoryRepository::class,
        LocationRepositoryInterface::class => LocationRepository::class,
        TrackRepositoryInterface::class => TrackRepository::class,
        WorkModeRepositoryInterface::class => WorkModeRepository::class,
        CountryRepositoryInterface::class => CountryRepository::class,
        StateRepositoryInterface::class => StateRepository::class,
        JobLevelRepositoryInterface::class => JobLevelRepository::class,
        JobRepositoryInterface::class => TalentJobRepository::class,
        ApplicationRepositoryInterface::class => ApplicationRepository::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->when(UserRepository::class)
            ->needs(Model::class)
            ->give(User::class);
    }
}