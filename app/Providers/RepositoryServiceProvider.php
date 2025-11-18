<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Repositories\{
    UserRepository
};
use App\Repositories\Interfaces\{
    UserRepositoryInterface
};
use Illuminate\Database\Eloquent\Model;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Binds interfaces to their implementations.
     * @var array<string, string>
     */
    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->when(UserRepository::class)
            ->needs(Model::class)
            ->give(User::class);

    }
}
