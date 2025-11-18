<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\Auth\GoogleAuthRequest;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly GoogleAuthInterface $googleService
    ) {}

    /**
     * Google login using an ID token from the frontend.
     */
    public function callback(GoogleAuthRequest $request)
    {
        try {
            // Retrieve Google user from token
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->token);

            if (!$googleUser) {
                return $this->error('Invalid Google token.');
            }

            $response = $this->googleService->handle($googleUser);

            return $this->successWithData(
                [
                    'user'  => $response['user'],
                    'token' => $response['token'],
                ],
                'Login successful'
            );

        } catch (\Exception $e) {
            return $this->error(
                'Google authentication failed.',
                status: \App\Enums\Http::BAD_REQUEST
            );
        }
    }
}
