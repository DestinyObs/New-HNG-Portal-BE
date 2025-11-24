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
        $this->googleAuthService = new GoogleAuthService($this->userService);
    }

    public function test_existing_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $result = $this->googleAuthService->handle([
            'email' => 'existing@example.com',
            'name' => 'Existing User',
        ]);

        $this->assertEquals('login', $result['mode']);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertNotEmpty($result['token']);
    }

    public function test_new_talent_user_can_signup()
    {
        $this->userService
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                $data['id'] = 1;
                $data['token'] = Str::random(10);

                return $data;
            });

        $result = $this->googleAuthService->handle([
            'email' => 'newtalent@example.com',
            'name' => 'New Talent',
        ], 'talent');

        $this->assertEquals('New', $result['firstname']);
        $this->assertEquals('Talent', $result['lastname']);
        $this->assertEquals('newtalent@example.com', $result['email']);
        $this->assertEquals('talent', $result['role']);
        $this->assertNotEmpty($result['password']);
    }

    public function test_new_company_user_can_signup_with_company_name_provided()
    {
        $this->userService
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                $data['id'] = 2;
                $data['token'] = Str::random(10);

                return $data;
            });

        $result = $this->googleAuthService->handle([
            'email' => 'company@example.com',
            'name' => 'ACME Inc',
            'company_name' => 'ACME Inc',
        ], 'company');

        $this->assertEquals('ACME Inc', $result['company_name']);
        $this->assertEquals('company', $result['role']);
        $this->assertEquals('ACME', $result['firstname']); // first word of name
        $this->assertEquals('Inc', $result['lastname']);    // second word of name
        $this->assertNotEmpty($result['password']);
    }

    public function test_new_company_user_infers_company_name_if_not_provided()
    {
        $this->userService
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                $data['id'] = 3;
                $data['token'] = Str::random(10);

                return $data;
            });

        $result = $this->googleAuthService->handle([
            'email' => 'company2@example.com',
            'name' => 'Inferred Company',
        ], 'company');

        $this->assertEquals('Inferred Company', $result['company_name']);
        $this->assertEquals('company', $result['role']);
    }

    public function test_signup_fails_without_role()
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
