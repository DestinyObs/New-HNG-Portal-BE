<?php

namespace App\Providers;

use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Services\Interfaces\{
    UserInterface,
    WaitlistInterface,
};
use App\Services\{
    UserService,
    WaitlistService,
};
use App\Services\Admin\CategoryService;
use App\Services\Employer\CompanyService;
use App\Services\Interfaces\Admin\CategoryServiceInterface;
use App\Services\Interfaces\Admin\CountryServiceInterface;
use App\Services\Interfaces\Admin\StateServiceInterface;
use App\Services\Interfaces\Admin\TrackServiceInterface;
use App\Services\Interfaces\Admin\WorkModeServiceInterface;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use App\Services\Interfaces\Auth\LoginInterface;
use App\Services\Interfaces\Auth\PasswordResetInterface;
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use App\Services\Talent\ProfileService;
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
        CategoryServiceInterface::class => \App\Services\Admin\CategoryService::class,
        ProfileServiceInterface::class => ProfileService::class,
        TrackServiceInterface::class => \App\Services\Admin\TrackService::class,
        WorkModeServiceInterface::class => \App\Services\Admin\WorkModeService::class,
        CountryServiceInterface::class => \App\Services\Admin\CountryService::class,
        StateServiceInterface::class => \App\Services\Admin\StateService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}