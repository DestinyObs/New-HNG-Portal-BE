<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\GoogleAuthRequest;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResource;

class GoogleAuthController extends Controller
{
    use ApiResponse;
    public function __construct(
        private readonly GoogleAuthInterface $googleService
    ) {}

    /**
     * Unified Google login/signup for both Talent and Employer
     */
    public function callback(GoogleAuthRequest $request): JsonResponse
    {
        try {
            // Convert Google token to user object
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->token);

            // Handle via service (login or signup)
            $response = $this->googleService->handle($googleUser, $request->role);


            return response()->json([
                'status' => true,
                'message' => "User Created or logged in successfully",
                'data' => [
                    'user' => $response['user'],
                    'token' => $response['token'],
                    'is_new_user' => $response['is_new_user'],
                ],
            ]);

        } catch (\Throwable $e) {
            return $this->error(
                'Google authentication failed.',
                status: \App\Enums\Http::BAD_REQUEST
            );
        }
    }
}