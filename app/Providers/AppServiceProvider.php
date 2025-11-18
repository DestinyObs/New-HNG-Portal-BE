<?php

namespace App\Providers;

use App\Services\FaqService;
use App\Services\Interfaces\FaqInterface;
use App\Services\Interfaces\JobInterface;
use App\Services\JobService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            JobInterface::class,
            JobService::class
        );

        $this->app->bind(
            FaqInterface::class,
            FaqService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
