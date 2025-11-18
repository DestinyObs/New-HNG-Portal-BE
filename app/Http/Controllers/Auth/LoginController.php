<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

        return $this->successWithData([
            'user'  => $response['user'],
            'token' => $response['token'], 
        ], 'Login successful');
    }
}