<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserDevice;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as GoogleUser;

class GoogleAuthService implements GoogleAuthInterface
{
    private function splitName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);

        return [
            $parts[0] ?? '',
            $parts[1] ?? '',
        ];
    }

    private function saveDevice($user)
    {
        UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'name'    => request()->userAgent(),
            ],
            [
                'last_activity_at' => now(),
            ]
        );
    }

    public function handle(GoogleUser $googleUser, string $role): array
    {
        $isNewUser = false;
        $email = $googleUser->getEmail();
        [$firstname, $lastname] = $this->splitName($googleUser->getName());

        $userCheck = User::where('email', $email)->first();

        if (!$userCheck) {
            $isNewUser = true;
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'role' => $role,
                'password' => Str::random(12),
            ]
        );

        $this->saveDevice($user);

        $token = $user->createToken('API Token')->plainTextToken;
        //
        return [
            'user' => $user,
            'token' => $token,
            'is_new_user' => $isNewUser
        ];
    }
}
