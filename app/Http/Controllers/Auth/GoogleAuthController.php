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
        $result = $this->googleAuthService->handle($data);

        return $this->successWithData($result, 'Authentication successful');
    }
    }
}
