<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        $this->registerPolicies();

        // Allow API docs access on the server
        Gate::define('viewApiDocs', function ($user = null) {
            return in_array(env('APP_ENV'), ['local', 'staging', 'production']);
        });
    }

}
