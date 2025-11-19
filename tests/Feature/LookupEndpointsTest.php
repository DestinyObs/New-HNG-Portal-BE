<?php

namespace Tests\Feature;

use App\Enums\Http;
use App\Models\Category;
use App\Models\Country;
use App\Models\JobType;
use App\Models\Location;
use App\Models\Skill;
use App\Models\State;
use App\Models\Tag;
use App\Models\Track;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LookupEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider resourceIndexProvider
     */
    public function test_can_list_resources(string $modelClass, string $endpoint): void
    {
        $modelClass::factory()->count(3)->create();

        $response = $this->getJson("api/{$endpoint}");

        $response->assertStatus(Http::OK->value)
            ->assertJson([
                'success' => true,
                'status' => Http::OK->value,
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * @dataProvider resourceShowProvider
     */
    public function test_can_show_resource(string $modelClass, string $endpoint): void
    {
        $model = $modelClass::factory()->create();

        $response = $this->getJson("api/{$endpoint}/{$model->id}");

        $response->assertStatus(Http::OK->value)
            ->assertJson([
                'success' => true,
                'status' => Http::OK->value,
            ])
            ->assertJsonPath('data.id', $model->id);
    }

    public function test_can_list_states_by_country(): void
    {
        $country = Country::factory()->create();
        State::factory()->count(2)->for($country)->create();
        State::factory()->create(); // should not appear

        $response = $this->getJson("api/countries/{$country->id}/states");

        $response->assertStatus(Http::OK->value)
            ->assertJson([
                'success' => true,
                'status' => Http::OK->value,
            ])
            ->assertJsonCount(2, 'data');

        $this->assertTrue(
            collect($response->json('data'))->every(fn (array $state) => $state['country_id'] === $country->id),
            'All returned states should belong to the requested country.'
        );
    }

    public function test_can_list_states(): void
    {
        State::factory()->count(4)->create();

        $response = $this->getJson('api/states');

        $response->assertStatus(Http::OK->value)
            ->assertJson([
                'success' => true,
                'status' => Http::OK->value,
            ])
            ->assertJsonCount(4, 'data');
    }

    public function test_can_show_state(): void
    {
        $state = State::factory()->create();

        $response = $this->getJson("api/states/{$state->id}");

        $response->assertStatus(Http::OK->value)
            ->assertJson([
                'success' => true,
                'status' => Http::OK->value,
            ])
            ->assertJsonPath('data.id', $state->id)
            ->assertJsonPath('data.country_id', $state->country_id);
    }

    public static function resourceIndexProvider(): array
    {
        return [
            'countries' => [Country::class, 'countries'],
            'tracks' => [Track::class, 'tracks'],
            'categories' => [Category::class, 'categories'],
            'locations' => [Location::class, 'locations'],
            'job types' => [JobType::class, 'job-types'],
            'tags' => [Tag::class, 'tags'],
            'skills' => [Skill::class, 'skills'],
        ];
    }

    public static function resourceShowProvider(): array
    {
        return [
            'country show' => [Country::class, 'countries'],
            'track show' => [Track::class, 'tracks'],
            'category show' => [Category::class, 'categories'],
            'location show' => [Location::class, 'locations'],
            'job type show' => [JobType::class, 'job-types'],
            'tag show' => [Tag::class, 'tags'],
            'skill show' => [Skill::class, 'skills'],
        ];
    }
}

