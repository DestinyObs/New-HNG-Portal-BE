<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\Auth\LoginInterface;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginInterface $loginService
    ) {}

    /**
     * Handle user login
     */
    public function __invoke(LoginRequest $request)
    {
        $response = $this->loginService->attempt(
            $request->validated()
        );
        // dd($response['user']);

        $user = new UserResource($response['user']);

        return $this->successWithData([
            'user' => $user,
            'token' => $response['token'],
        ], 'Login successful');
    }
}
