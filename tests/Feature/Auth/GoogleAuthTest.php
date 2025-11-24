<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\Auth\GoogleAuthService;
use App\Services\Interfaces\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class GoogleAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_existing_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $mockUserService = Mockery::mock(UserInterface::class);

        $googleAuthService = new GoogleAuthService($mockUserService);

        $result = $googleAuthService->handle([
            'email' => 'existing@example.com',
            'name' => 'Existing User',
        ]);

        $this->assertEquals('login', $result['mode']);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertArrayHasKey('token', $result);
    }

    public function test_new_talent_user_can_signup()
    {
        $mockUserService = Mockery::mock(UserInterface::class);
        $mockUserService->shouldReceive('create')->once()->andReturnUsing(function ($data) {
            return array_merge($data, ['mode' => 'signup']);
        });

        $googleAuthService = new GoogleAuthService($mockUserService);

        $data = [
            'email' => 'newtalent@example.com',
            'name' => 'New Talent',
            'role' => 'talent',
        ];

        $result = $googleAuthService->handle($data);

        $this->assertEquals('signup', $result['mode']);
        $this->assertEquals('New', $result['firstname']);
        $this->assertEquals('Talent', $result['lastname']);
        $this->assertEquals('talent', $result['role']);
        $this->assertEquals('newtalent@example.com', $result['email']);
        $this->assertArrayHasKey('password', $result);
    }

    public function test_new_company_user_can_signup_with_company_name_provided()
    {
        $mockUserService = Mockery::mock(UserInterface::class);
        $mockUserService->shouldReceive('create')->once()->andReturnUsing(function ($data) {
            return array_merge($data, ['mode' => 'signup']);
        });

        $googleAuthService = new GoogleAuthService($mockUserService);

        $data = [
            'email' => 'company@example.com',
            'name' => 'ACME Inc',
            'role' => 'company',
            'company_name' => 'ACME Inc',
        ];

        $result = $googleAuthService->handle($data);

        $this->assertEquals('signup', $result['mode']);
        $this->assertEquals('ACME Inc', $result['company_name']);
        $this->assertEquals('company', $result['role']);
    }

    public function test_signup_fails_without_role()
    {
        $mockUserService = Mockery::mock(UserInterface::class);
        $googleAuthService = new GoogleAuthService($mockUserService);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Role is required for new users.');

        $googleAuthService->handle([
            'email' => 'newuser@example.com',
            'name' => 'No Role User',
        ]);
    }

    public function test_signup_fails_without_email()
    {
        $mockUserService = Mockery::mock(UserInterface::class);
        $googleAuthService = new GoogleAuthService($mockUserService);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Email is required.');

        $googleAuthService->handle([
            'name' => 'No Email',
            'role' => 'talent',
        ]);
    }
}
