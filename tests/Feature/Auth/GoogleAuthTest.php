<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Return a fake Google user object.
     */
    protected function fakeGoogleUser($email = 'test@example.com', $name = 'Test User')
    {
        return new class($email, $name) {
            private $email;
            private $name;

            public function __construct($email, $name)
            {
                $this->email = $email;
                $this->name = $name;
            }

            public function getEmail()  { return $this->email; }
            public function getName()   { return $this->name; }
            public function getAvatar() { return 'https://example.com/avatar.png'; }
            public function getId()     { return 'google-12345'; }
        };
    }

    /**
     * Mock Socialite to return our fake Google user.
     */
    private function mockSocialite($googleUser)
    {
        $providerMock = \Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $providerMock->shouldReceive('stateless')->andReturnSelf();
        $providerMock->shouldReceive('userFromToken')->andReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($providerMock);
    }

    /** @test */
    public function existing_user_can_login()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $googleUser = $this->fakeGoogleUser('test@example.com', 'Existing User');
        $this->mockSocialite($googleUser);

        $response = $this->postJson('/api/auth/google/callback', [
            'role' => 'talent',
            'google_token' => 'dummy-token',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function new_talent_user_can_signup()
    {
        $googleUser = $this->fakeGoogleUser('newtalent@example.com', 'New Talent');
        $this->mockSocialite($googleUser);

        $response = $this->postJson('/api/auth/google/callback', [
            'role' => 'talent',
            'google_token' => 'dummy-token',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'newtalent@example.com']);
    }

    /** @test */
    public function new_company_user_can_signup_with_company_name()
    {
        $googleUser = $this->fakeGoogleUser('newcompany@example.com', 'New Company');
        $this->mockSocialite($googleUser);

        $response = $this->postJson('/api/auth/google/callback', [
            'role' => 'company',
            'company_name' => 'HNG',
            'google_token' => 'dummy-token',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'newcompany@example.com']);
    }

    /** @test */
    public function new_company_user_infers_company_name()
    {
        $googleUser = $this->fakeGoogleUser('autoinfer@example.com', 'Auto Infer');
        $this->mockSocialite($googleUser);

        $response = $this->postJson('/api/auth/google/callback', [
            'role' => 'company',
            'google_token' => 'dummy-token',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'autoinfer@example.com']);
    }

    /** @test */
    public function token_decoding_failure_throws_exception()
    {
        // Break Socialite on purpose
        $providerMock = \Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $providerMock->shouldReceive('stateless')->andReturnSelf();
        $providerMock->shouldReceive('userFromToken')->andThrow(new \Exception("Invalid token"));

        Socialite::shouldReceive('driver')->with('google')->andReturn($providerMock);

        $this->expectException(\Exception::class);

        $this->postJson('/api/auth/google/callback', [
            'role' => 'talent',
            'google_token' => 'broken-token',
        ]);
    }
}
