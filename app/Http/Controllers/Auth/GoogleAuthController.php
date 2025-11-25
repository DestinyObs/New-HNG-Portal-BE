<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleAuthRequest;
use App\Services\Interfaces\Auth\GoogleAuthInterface;

class GoogleAuthController extends Controller
{
    public function __construct(private readonly GoogleAuthInterface $googleAuthService) {}

    public function handle(GoogleAuthRequest $request)
    {
        $data = $request->validated();

        $result = $this->googleAuthService->handle(
            $data['google_token'],
            $data['role'] ?? null,
            $data['company_name'] ?? null,
        );

        return $this->successWithData($result, 'Authentication successful');
    }
}
