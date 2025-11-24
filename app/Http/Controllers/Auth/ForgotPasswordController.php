<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Interfaces\Auth\PasswordResetInterface;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private readonly PasswordResetInterface $passwordResetService
    ) {}

    /**
     * Send password reset link
     */
    public function store(ForgotPasswordRequest $request)
    {
        $this->passwordResetService->sendResentLink(
            $request->validated('email')
        );

        return $this->success('Password reset link sent successfully');
    }

    /**
     * Reset user password
     */
    public function update(ResetPasswordRequest $request)
    {
        $this->passwordResetService->resetPassword(
            $request->validated('email'),
            $request->validated('token'),
            $request->validated('password')
        );

        return $this->success('Password reset successfully');
    }
}
