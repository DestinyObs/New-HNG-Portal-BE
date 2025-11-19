<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function __construct(
        private readonly GoogleAuthInterface $googleService
    ) {}

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect(); 
    }

    // Step 2: Handle Google callback
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $response = $this->googleService->handle($googleUser);

        return $this->successWithData([
            'user'  => $response['user'],
            'token' => $response['token'],
        ], 'Login successful');
    }
}
