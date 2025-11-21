<?php

namespace App\Providers;

use App\Models\{
    Category,
    Country,
    JobType,
    Location,
    Skill,
    State,
    Tag,
    Track,
    User
};
use Illuminate\Support\ServiceProvider;
use App\Repositories\{
    CategoryRepository,
    CountryRepository,
    JobTypeRepository,
    LocationRepository,
    SkillRepository,
    StateRepository,
    TagRepository,
    TrackRepository,
    UserRepository
};
use App\Repositories\Interfaces\{
    CategoryRepositoryInterface,
    CountryRepositoryInterface,
    JobTypeRepositoryInterface,
    LocationRepositoryInterface,
    SkillRepositoryInterface,
    StateRepositoryInterface,
    TagRepositoryInterface,
    TrackRepositoryInterface,
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
        CountryRepositoryInterface::class => CountryRepository::class,
        StateRepositoryInterface::class => StateRepository::class,
        TrackRepositoryInterface::class => TrackRepository::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        LocationRepositoryInterface::class => LocationRepository::class,
        JobTypeRepositoryInterface::class => JobTypeRepository::class,
        TagRepositoryInterface::class => TagRepository::class,
        SkillRepositoryInterface::class => SkillRepository::class,
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

        $this->app->when(CountryRepository::class)
            ->needs(Model::class)
            ->give(Country::class);

        $this->app->when(StateRepository::class)
            ->needs(Model::class)
            ->give(State::class);

        $this->app->when(TrackRepository::class)
            ->needs(Model::class)
            ->give(Track::class);

        $this->app->when(CategoryRepository::class)
            ->needs(Model::class)
            ->give(Category::class);

        $this->app->when(LocationRepository::class)
            ->needs(Model::class)
            ->give(Location::class);

        $this->app->when(JobTypeRepository::class)
            ->needs(Model::class)
            ->give(JobType::class);

        $this->app->when(TagRepository::class)
            ->needs(Model::class)
            ->give(Tag::class);

        $this->app->when(SkillRepository::class)
            ->needs(Model::class)
            ->give(Skill::class);
    }
}
