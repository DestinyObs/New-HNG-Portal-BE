<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use App\Services\Interfaces\UserInterface;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class GoogleAuthService implements GoogleAuthInterface
{
    public function __construct(protected UserInterface $userService) {}

    public function handle(
        string $googleToken,
        ?string $role = null,
        ?string $companyName = null
    ): array |\Exception {
        // Retrieve user info from Google
        try {
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($googleToken);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid Google token provided.');
        }

        $email = $googleUser->getEmail();

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // Always return user + token
            return [
                'user'  => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ];
        }

        // SIGNUP FLOW — role is required
        if (!$role) {
            throw new \InvalidArgumentException('Role is required for new user signup.');
        }

        $generatedPassword = Str::random(12);

        $data = [
            'email' => $email,
            'password' => $generatedPassword,
            'role' => $role,
            'email_verified_at' => now(),
        ];

        if ($role === 'talent') {
            $parts = explode(' ', $googleUser->getName(), 2);
            $data['firstname'] = $parts[0] ?? null;
            $data['lastname']  = $parts[1] ?? null;
        }

        if ($role === 'company') {
            $data['company_name'] = $companyName ?? $googleUser->getName();
        }
        

        return $this->userService->create($data);
    }

}
