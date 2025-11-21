<?php

namespace App\Providers;

use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Services\Interfaces\{
    CategoryInterface,
    CountryInterface,
    JobTypeInterface,
    LocationInterface,
    SkillInterface,
    StateInterface,
    TagInterface,
    TrackInterface,
    UserInterface,
    WaitlistInterface,
};
use App\Services\{
    CategoryService,
    CountryService,
    JobTypeService,
    LocationService,
    SkillService,
    StateService,
    TagService,
    TrackService,
    UserService,
    WaitlistService,
};
use App\Services\Employer\CompanyService;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use App\Services\Interfaces\Auth\LoginInterface;
use App\Services\Interfaces\Auth\PasswordResetInterface;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{

    /**
     * Binds interfaces to their implementations.
     * @var array<string, string>
     */
    public $bindings = [
        WaitlistInterface::class => WaitlistService::class,
        CompanyRepositoryInterface::class => CompanyService::class,
        UserInterface::class => UserService::class,
        LoginInterface::class => \App\Services\Auth\LoginService::class,
        PasswordResetInterface::class => \App\Services\Auth\PasswordResetService::class,
        GoogleAuthInterface::class => \App\Services\Auth\GoogleAuthService::class,
        CountryInterface::class => CountryService::class,
        StateInterface::class => StateService::class,
        TrackInterface::class => TrackService::class,
        CategoryInterface::class => CategoryService::class,
        LocationInterface::class => LocationService::class,
        JobTypeInterface::class => JobTypeService::class,
        TagInterface::class => TagService::class,
        SkillInterface::class => SkillService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
