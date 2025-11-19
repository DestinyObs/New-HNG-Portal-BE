<?php

namespace App\Providers;

use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Services\Interfaces\{
    WaitlistInterface,
};
use App\Services\{
    WaitlistService,
};
use App\Services\Employer\CompanyService;
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
