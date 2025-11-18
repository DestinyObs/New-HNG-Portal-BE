<?php 

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserDevice;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as GoogleUser;

class GoogleAuthService implements GoogleAuthInterface
{
    public function handle(GoogleUser $googleUser): array
    {
        // Split name into firstname + lastname
        [$firstname, $lastname] = $this->splitName($googleUser->getName());

        // Create or find user
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'firstname'         => $firstname,
                'lastname'          => $lastname,
                'password'  => Str::random(12), 
            ]
        );

        $this->saveDevice($user);

        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

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
}
