<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use App\Services\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthService implements GoogleAuthInterface
{
    public function __construct(protected UserInterface $userService) {}

    public function handle(array $googleUserData, ?string $role = null): array|\Exception
    {
        $email = $googleUserData['email'] ?? null;

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // LOGIN FLOW
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        // SIGNUP FLOW
        if (! $role) {
            throw new \InvalidArgumentException('Role is required for new users.');
        }

        $nameParts = explode(' ', $googleUserData['name'], 2);
        $firstname = $nameParts[0] ?? null;
        $lastname = $nameParts[1] ?? null;
        $generatedPassword = Str::random(12);

        $data = [
            'email' => $email,
            'password' => $generatedPassword,
            'role' => $role,
        ];

        if ($role === 'talent') {
            $nameParts = explode(' ', $googleUserData['name'], 2);
            $data['firstname'] = $nameParts[0] ?? null;
            $data['lastname'] = $nameParts[1] ?? null;
        }

        if ($role === 'company') {
            $data['company_name'] = $googleUserData['company_name'] ?? $googleUserData['name'];
        }

        // Delegate to UserService to handle full signup (OTP, role, company/userbio, auth)
        return $this->userService->create($data);
    }
}
