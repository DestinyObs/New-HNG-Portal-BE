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

// Services for talent role
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use App\Services\Talent\ProfileService;

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
        ProfileServiceInterface::class => ProfileService::class,
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
