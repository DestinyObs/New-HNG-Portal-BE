<?php

namespace App\Providers;

use App\Services\Interfaces\{
    UserInterface,
    WaitlistInterface,
};
use App\Services\{
    UserService,
    WaitlistService,
};
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
        UserInterface::class => UserService::class,
        LoginInterface::class => \App\Services\Auth\LoginService::class,
        PasswordResetInterface::class => \App\Services\Auth\PasswordResetService::class,
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
