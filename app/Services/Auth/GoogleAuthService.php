<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use App\Services\Interfaces\UserInterface;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthService implements GoogleAuthInterface
{
    public function __construct(protected UserInterface $userService) {}

    public function handle(string $googleToken, ?string $role = null, ?string $companyName = null): array | User | \Exception
    {
        // Retrieve user info from Google using the token

        try {
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($googleToken);
            info('GOOGLE USER', ['user' => $googleUser]);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid Google token provided.');
        } catch (\Throwable $e) {
            info('GOOGLE ERROR', ['error' => $e->getMessage()]);
            throw new \Exception('An error occurred while authenticating with Google. Please try again later.');
        }

        $googleUserData = [
            'email' => $googleUser->getEmail(),
            'name' => $googleUser->getName(),
            'company_name' => $companyName,
        ];

        $email = $googleUserData['email'] ?? null;

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // LOGIN FLOW

            return $user;
        }

        // SIGNUP FLOW

        if (!$role) {
            throw new \InvalidArgumentException('Role is required for new user signup.');
        }

        $nameParts = explode(' ', $googleUserData['name'], 2);
        $generatedPassword = Str::random(12);

        $data = [
            'email' => $email,
            'password' => $generatedPassword,
            'role' => $role,
            'email_verified_at' => now(),
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
