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
            // Always returns: ['user' => model, 'token' => token]
            $result = $this->googleAuthService->handle(
                $data['google_token'],
                $data['role'] ?? null,
                $data['company_name'] ?? null
            );

            // Wrap user in resource
            $user = new UserResource($result['user']);

            // Eager load relationships
            $result['user']->load([
                'company',
                'bio',
                'skills',
                'experiences',
                'verification',
                'preferences',
            ]);

            return $this->successWithData([
                'user'  => $user,
                'token' => $result['token'],
            ], 'Authentication successful!');

        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage());
        }
    }

}
