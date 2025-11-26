<?php

namespace Tests\Feature;

use App\Mail\WaitlistJoined;
use App\Models\Waitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WaitlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_join_waitlist()
    {
        Mail::fake();

        $response = $this->postJson('api/waitlist', [
            'full_name' => 'John Doe',
            'email' => 'test@example.com', // Use a test email, not your real one
            'role' => 'talent',
        ]);

        $response->assertStatus(201);
        Mail::assertSent(WaitlistJoined::class);
    }

    public function test_waitlist_requires_valid_email()
    {
        $response = $this->postJson('api/waitlist', [
            'full_name' => 'John Doe',
            'email' => 'invalid-email',
            'role' => 'talent',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_waitlist_requires_unique_email()
    {
        Waitlist::create([
            'full_name' => 'Existing User',
            'email' => 'existing@example.com',
            'role' => 'company',
        ]);

        $response = $this->postJson('api/waitlist', [
            'full_name' => 'John Doe',
            'email' => 'existing@example.com', // Same email
            'role' => 'talent',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
