<?php

namespace App\Http\Controllers;

use App\Enums\Http;
use App\Http\Requests\OtpTokenRequest;
use App\Http\Resources\UserResource;
use App\Mail\CompanyRegistered;
use App\Mail\OtpVerification;
use App\Mail\UserRegistered;
use App\Models\Company;
use App\Models\OtpToken;
use App\Models\User;
use App\Services\Interfaces\Auth\LoginInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpTokenController extends Controller
{
    public function __construct(
        private readonly LoginInterface $loginService
    ) {}

    public function verifyOtp(OtpTokenRequest $request)
    {

        $user = $request->user();
        // dd($user);
        if ($user->hasVerifiedEmail()) {
            return $this->success(
                'Account has already been verified!.',
                Http::OK,
            );
        }

        $plainOtp = $request->input('otp');
        $hashedOtp = OtpToken::where('user_id', $user->id)->first();

        if (!$hashedOtp || $hashedOtp->expired_at < now()) {
            // Error invalid otp or Expired token
            return $this->error(
                'OTP is invalid or has expired.',
                Http::UNAUTHORIZED
            );
        }

        // OTP is valid
        if (Hash::check($plainOtp, $hashedOtp->hashed_token)) {
            // And mark the OTP as used or delete it
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            // Get company details
            $company = Company::where('user_id', $user->id)->first();
            if ($company) {
                Mail::to($company->official_email)->send(new CompanyRegistered($user, $company));
            } else {
                Mail::to($user->email)->send(new UserRegistered($user));
            }

            // Delete Otp
            $hashedOtp->delete();

            $response = $this->loginService->attempt(
                $user,
            );
            // dd($response['user']);

            $user = new UserResource($response['user']);


            //  Successful response
            // return $this->success(
            //     'OTP verified successfully.',
            //     Http::OK,
            // );

            return $this->successWithData([
                'user' => $user,
                'token' => $response['token'],
            ], 'OTP verified successfully.');
        } else {
            // OTP is invalid
            return $this->error(
                'Otp is invalid.',
                Http::UNAUTHORIZED
            );
        }
    }

    public function resendOtp(Request $request)
    {
        $user = $request->user();
        if ($user->email_verification) {
            return $this->success(
                'Account has already been verified!.',
                Http::OK,
            );
        }

        $user = $request->user();
        // ? Generate OTP for user and store in otp_tokens
        $otpCode = $this->generateOtp($user);

        // ? Send email to user (commented out for now)
        Mail::to($user->email)->send(new OtpVerification($user, $otpCode));

        return new UserResource($user);
    }

    /**
     * Generate a 4-digit OTP, store hashed in DB with 10-min expiry
     */
    private function generateOtp(User $user): string
    {
        // ? Generate a secure 6-digit OTP
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // ? Store hashed OTP in otp_tokens table
        $existing = OtpToken::where('user_id', $user->id)->first();
        $hashed = Hash::make($otpCode);
        $expiresAt = now()->addMinutes(10);

        if ($existing) {
            // Update token and expiry for existing record
            $existing->update([
                'hashed_token' => $hashed,
                'expired_at' => $expiresAt,
            ]);
        } else {
            // Create a new record entirely
            OtpToken::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'hashed_token' => $hashed,
                'expired_at' => $expiresAt,
            ]);
        }

        return (string) $otpCode;
    }
}