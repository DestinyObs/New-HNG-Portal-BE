<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\Auth\GoogleAuthService;
use App\Services\Interfaces\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected GoogleAuthService $service;
    protected $userServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock UserInterface
        $this->userServiceMock = Mockery::mock(UserInterface::class);

        $this->service = new GoogleAuthService($this->userServiceMock);
    }

    private function fakeGoogleUser($email = 'test@example.com', $name = 'Test User')
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
        };
    }

    private function mockSocialite($googleUser)
    {
        $providerMock = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $providerMock->shouldReceive('stateless')->andReturnSelf();
        $providerMock->shouldReceive('userFromToken')->andReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($providerMock);
    }

    /** @test */
    public function existing_user_can_login()
    {
        $user = User::factory()->create(['email' => 'existing@example.com']);
        $googleUser = $this->fakeGoogleUser('existing@example.com', 'Existing User');
        $this->mockSocialite($googleUser);

        $result = $this->service->handle('fake-token');

        $this->assertEquals($user->id, $result['user']->id);
        $this->assertArrayHasKey('token', $result);
    }

    /** @test */
    public function new_talent_user_can_signup()
    {
        $googleUser = $this->fakeGoogleUser('talent@example.com', 'John Doe');
        $this->mockSocialite($googleUser);

        $this->userServiceMock->shouldReceive('create')->once()
            ->andReturnUsing(function ($data) {
                return array_merge($data, ['id' => 1, 'token' => Str::random(10)]);
            });

        $result = $this->service->handle('fake-token', 'talent');

        $this->assertEquals('John', $result['firstname']);
        $this->assertEquals('Doe', $result['lastname']);
        $this->assertEquals('talent', $result['role']);
        $this->assertNotEmpty($result['password']);
    }

    /** @test */
    public function new_company_user_can_signup_with_company_name()
    {
        $googleUser = $this->fakeGoogleUser('company@example.com', 'ACME Inc');
        $this->mockSocialite($googleUser);

        $this->userServiceMock->shouldReceive('create')->once()
            ->andReturnUsing(fn($data) => array_merge($data, ['id' => 2, 'token' => Str::random(10)]));

        $result = $this->service->handle('fake-token', 'company', 'ACME Inc');

        $this->assertEquals('ACME Inc', $result['company_name']);
        $this->assertEquals('company', $result['role']);
    }

    /** @test */
    public function new_company_user_infers_company_name()
    {
        $googleUser = $this->fakeGoogleUser('infer@example.com', 'Inferred Corp');
        $this->mockSocialite($googleUser);

        $this->userServiceMock->shouldReceive('create')->once()
            ->andReturnUsing(fn($data) => array_merge($data, ['id' => 3, 'token' => Str::random(10)]));

        $result = $this->service->handle('fake-token', 'company');

        $this->assertEquals('Inferred Corp', $result['company_name']);
        $this->assertEquals('company', $result['role']);
    }

    /** @test */
    public function invalid_google_token_throws_exception()
    {
        Socialite::shouldReceive('driver->stateless->userFromToken')
            ->andThrow(new \Exception('Invalid token'));

        $this->expectException(\InvalidArgumentException::class);

        $this->service->handle('bad-token');
    }

    /** @test */
    public function signup_without_role_throws_exception()
    {
        $googleUser = $this->fakeGoogleUser('norole@example.com', 'No Role');
        $this->mockSocialite($googleUser);

        $this->expectException(\InvalidArgumentException::class);

        $this->service->handle('fake-token');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
