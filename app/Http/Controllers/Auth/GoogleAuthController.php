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

    // Legacy redirect/callback methods for Socialite web flow have been
    // commented out because this application now uses a frontend-driven
    // token flow (POST /api/auth/google) where the frontend exchanges the
    // OAuth handshake with Google and sends `access_token` -> backend.
    //
    // If you need to re-enable the old redirect flow, uncomment the
    // methods below and restore the corresponding routes in `routes/api.php`.

    /*
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
    */

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
        ], 'Login successful');
    }
}
