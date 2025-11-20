<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpTokenRequest;
use App\Models\OtpToken;
use Illuminate\Http\Request;
use App\Mail\CompanyRegistered;
use App\Mail\UserRegistered;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpTokenController extends Controller
{
    public function verifyOtp(OtpTokenRequest $request)
    {

        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Account has already been verified!.'
            ], 201);
        }
        $plainOtp = $request->input('otp');
        $hashedOtp = OtpToken::where('user_id', $user->id)->first();
        if (!$hashedOtp || $hashedOtp->expired_at < now()) {
            // Error invalid otp or Expired token

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

            //  Successful response
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.'
            ], 200);

        } else {
            // OTP is invalid
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.'
            ], 401);
        }
    }

    public function resendOtp(Request $request){
            // $user = $request->user();
            // //? Generate OTP for user and store in otp_tokens
            // $otpCode = $this->generateOtp($user);

            // //? Send email to user (commented out for now)
            // Mail::to($user->email)->send(new OtpVerification($user, $otpCode));
            // return $user;
    }

    /**
     * Generate a 4-digit OTP, store hashed in DB with 10-min expiry
     */
    private function generateOtp(User $user): string
    {
        //? Generate a secure 6-digit OTP
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        //? Store hashed OTP in otp_tokens table
        OtpToken::updateOrInsert([
            'user_id' => $user->id
        ], [
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'hashed_token' => Hash::make($otpCode),
            'expired_at' => now()->addMinutes(10),
        ]);

        return (string) $otpCode;
    }
}
