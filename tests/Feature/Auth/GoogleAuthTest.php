<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        // Ensure migrations run (if needed)
        // Artisan::call('migrate');
    }

    public function test_existing_user_can_login_with_token()
    {
        $user = User::factory()->create(['email' => 'existing@example.com']);

        $token = 'fake-token-existing';

        // Mock Socialite provider behaviour
        $provider = Mockery::mock();
        $socialiteUser = Mockery::mock(SocialiteUserContract::class);
        $socialiteUser->shouldReceive('getEmail')->andReturn('existing@example.com');
        $socialiteUser->shouldReceive('getName')->andReturn('Existing User');

        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('userFromToken')->with($token)->andReturn($socialiteUser);

        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->postJson('/api/auth/google', ['access_token' => $token]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
    }

    public function test_new_user_signup_requires_role_and_company_when_company()
    {
        $token = 'fake-token-new';

        $provider = Mockery::mock();
        $socialiteUser = Mockery::mock(SocialiteUserContract::class);
        $socialiteUser->shouldReceive('getEmail')->andReturn('newcompany@example.com');
        $socialiteUser->shouldReceive('getName')->andReturn('New Company');

        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('userFromToken')->with($token)->andReturn($socialiteUser);

        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        // Missing role -> should return 422
        $response = $this->postJson('/api/auth/google', ['access_token' => $token]);
        $response->assertStatus(422);

        // Role company but missing company_name -> try inference and succeed for example.com
        $response = $this->postJson('/api/auth/google', ['access_token' => $token, 'role' => 'company']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);

        // Role company with company_name -> explicit success
        $response = $this->postJson('/api/auth/google', ['access_token' => $token, 'role' => 'company', 'company_name' => 'ACME Ltd']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
    }

    public function test_new_user_signup_with_talent_role_succeeds()
    {
        $token = 'fake-token-talent';

        $provider = Mockery::mock();
        $socialiteUser = Mockery::mock(SocialiteUserContract::class);
        $socialiteUser->shouldReceive('getEmail')->andReturn('newtalent@example.com');
        $socialiteUser->shouldReceive('getName')->andReturn('New Talent');

        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('userFromToken')->with($token)->andReturn($socialiteUser);

        \Laravel\Socialite\Facades\Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->postJson('/api/auth/google', ['access_token' => $token, 'role' => 'talent']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
    }
}
