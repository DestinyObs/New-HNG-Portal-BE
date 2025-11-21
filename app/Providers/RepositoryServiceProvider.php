<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

use Illuminate\Database\Eloquent\Model;

// Repositories
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;

//? Repositories for talent role
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Repositories\Talent\ProfileRepository;

//? Respositories for admin role
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\CountryRepository;
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\StateRepository;
use App\Repositories\Admin\TrackRepository;
use App\Repositories\Admin\WorkModeRepository;
use App\Repositories\Interfaces\Admin\CountryRepositoryInterface;
use App\Repositories\Interfaces\Admin\LocationRepositoryInterface;
use App\Repositories\Interfaces\Admin\StateRepositoryInterface;
use App\Repositories\Interfaces\Admin\TrackRepositoryInterface;
use App\Repositories\Interfaces\Admin\WorkModeRepositoryInterface;
// Services for talent role
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use App\Services\Talent\ProfileService;

//? Services for admin role
use App\Services\Interfaces\Admin\CategoryServiceInterface;
use App\Services\Admin\CategoryService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Binds interfaces to their implementations.
     * @var array<string, string>
     */
    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,

        //? TALANET PROFILE BINDINGS
        ProfileRepositoryInterface::class => ProfileRepository::class,

        //? ADMIN CATEGORY BINDINGS
        CategoryRepositoryInterface::class => CategoryRepository::class,
        LocationRepositoryInterface::class => LocationRepository::class,
        TrackRepositoryInterface::class => TrackRepository::class,
        WorkModeRepositoryInterface::class => WorkModeRepository::class,
        CountryRepositoryInterface::class => CountryRepository::class,
        StateRepositoryInterface::class => StateRepository::class,

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