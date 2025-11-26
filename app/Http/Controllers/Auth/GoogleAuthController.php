<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleAuthRequest;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use App\Http\Resources\UserResource;

class GoogleAuthController extends Controller
{
    public function __construct(private readonly GoogleAuthInterface $googleAuthService) {}

    public function handle(GoogleAuthRequest $request)
    {
        $data = $request->validated();

        try {
            $result = $this->googleAuthService->handle(
                $data['google_token'],
                $data['role'] ?? null,
                $data['company_name'] ?? null,
            );


            if (!is_array($result)) {
                // Wrap user
                $user = new UserResource($result);

                // Eager load relationships
                $user->load([
                    'company',
                    'bio',
                    'skills',
                    'experiences',
                    'verification',
                    'preferences',
                ]);

                // Build response
                $response = [
                    'user'  => $user,
                    'token' => $user->createToken('auth_token')->plainTextToken,
                ];
            } else {
                return $this->successWithData([
                    'user' => $result,
                ], 'Authentication successful!');
            }

            return $this->successWithData([
                'user' => $response['user'],
                'token' => $response['token'],
            ], 'Authentication successful!');

        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        }
    }
}
