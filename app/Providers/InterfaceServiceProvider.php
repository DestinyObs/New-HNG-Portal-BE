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
        GoogleAuthInterface::class => \App\Services\Auth\GoogleAuthService::class
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
