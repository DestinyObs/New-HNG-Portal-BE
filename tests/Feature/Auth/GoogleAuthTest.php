<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\Auth\GoogleAuthService;
use App\Services\Interfaces\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected UserInterface $userService;

    protected GoogleAuthService $googleAuthService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock UserService
        $this->userService = Mockery::mock(UserInterface::class);

        // Service under test
        $this->googleAuthService = new GoogleAuthService($this->userService);
    }

    /** @test */
    public function existing_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $result = $this->googleAuthService->handle([
            'email' => 'existing@example.com',
            'name' => 'Existing User',
        ]);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertNotEmpty($result['token']);
    }

    /** @test */
    public function new_talent_user_can_signup()
    {
        $this->userService
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                // Mimic UserService response
                return array_merge($data, [
                    'id' => 1,
                    'token' => Str::random(10),
                ]);
            });

        $result = $this->googleAuthService->handle([
            'email' => 'talent@example.com',
            'name' => 'John Doe',
        ], 'talent');

        $this->assertEquals('John', $result['firstname']);
        $this->assertEquals('Doe', $result['lastname']);
        $this->assertEquals('talent', $result['role']);
        $this->assertNotEmpty($result['password']);
    }

    /** @test */
    public function new_company_user_can_signup_with_company_name()
    {
        $this->userService
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                return array_merge($data, [
                    'id' => 2,
                    'token' => Str::random(10),
                ]);
            });

        $result = $this->googleAuthService->handle([
            'email' => 'company@example.com',
            'name' => 'ACME Inc',
            'company_name' => 'ACME Inc',
        ], 'company');

        $this->assertEquals('ACME Inc', $result['company_name']);
        $this->assertEquals('company', $result['role']);
        $this->assertNotEmpty($result['password']);
    }

    /** @test */
    public function new_company_user_infers_company_name_when_not_provided()
    {
        $this->userService
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                return array_merge($data, [
                    'id' => 3,
                    'token' => Str::random(10),
                ]);
            });

        $result = $this->googleAuthService->handle([
            'email' => 'infer@example.com',
            'name' => 'Inferred Corp',
        ], 'company');

        $this->assertEquals('Inferred Corp', $result['company_name']);
        $this->assertEquals('company', $result['role']);
    }

    /** @test */
    public function signup_fails_without_role()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Role is required for new users.');

        $this->googleAuthService->handle([
            'email' => 'norole@example.com',
            'name' => 'No Role User',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
