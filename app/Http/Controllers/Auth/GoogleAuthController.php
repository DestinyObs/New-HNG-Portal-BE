<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleTokenRequest;
use App\Services\Interfaces\Auth\GoogleAuthInterface;

class GoogleAuthController extends Controller
{
    public function __construct(
        private readonly GoogleAuthInterface $googleService
    ) {}

    /**
     * Handle frontend-provided Google access token (mobile/SPA flow)
     */
    public function handleToken(GoogleTokenRequest $request)
    {
        $response = $this->googleService->handleToken(
            $request->get('access_token'),
            $request->get('role'),
            $request->get('company_name')
        );

        return $this->successWithData([
            'user' => $response['user'],
            'token' => $response['token'],
        ], 'Google authentication successful');
    }
}
