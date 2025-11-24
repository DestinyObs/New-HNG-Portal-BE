<?php

namespace App\Services\Auth;

use App\Mail\OtpVerification;
use App\Models\Company;
use App\Models\OtpToken;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\Interfaces\Auth\GoogleAuthInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as GoogleUser;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService implements GoogleAuthInterface
{
    public function handle(GoogleUser $googleUser): array
    {
        return $this->processGoogleUser($googleUser, null);
    }

    public function handleToken(string $accessToken, ?string $role = null, ?string $companyName = null): array
    {
        $googleUser = Socialite::driver('google')->stateless()->userFromToken($accessToken);

        return $this->processGoogleUser($googleUser, $role, $companyName);
    }

    /**
     * Shared processing for a Google user; handles both existing and new users.
     */
    private function processGoogleUser(GoogleUser $googleUser, ?string $role = null, ?string $companyName = null): array
    {
        [$firstname, $lastname] = $this->splitName($googleUser->getName());

        $user = User::where('email', $googleUser->getEmail())->first();

        // External -> internal role mapping used both for validation and assignment
        $roleMap = [
            'talent' => 'talent',
            'company' => 'employer',
            'admin' => 'admin',
        ];

        // Existing user -> login. But if the caller provided a role, ensure it matches
        // the user's already-assigned role(s). We do NOT allow changing roles via
        // this token endpoint (signup vs login separation).
        if ($user) {
            if ($role) {
                if (! isset($roleMap[$role])) {
                    Log::warning('Google auth attempted with invalid role for existing user', ['role' => $role, 'email' => $googleUser->getEmail()]);
                    throw ValidationException::withMessages([
                        'role' => ['Invalid role provided.'],
                    ]);
                }

                $expectedInternal = $roleMap[$role];
                if (! $user->hasRole($expectedInternal)) {
                    throw ValidationException::withMessages([
                        'role' => ["User already exists with a different role and cannot sign up as '{$role}'."],
                    ]);
                }
            }

            $this->saveDevice($user);

            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        }

        // New user -> create, assign role, create company for employers, generate OTP, send mail, return token
        $password = Str::random(12);

        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $googleUser->getEmail(),
            'password' => $password,
        ]);

        // For signup: role is required and must be valid.
        if (! $role) {
            throw ValidationException::withMessages([
                'role' => ['Role is required for Google signup.'],
            ]);
        }

        if (! isset($roleMap[$role])) {
            Log::warning('Google signup attempted with invalid role', ['role' => $role, 'email' => $googleUser->getEmail()]);
            throw ValidationException::withMessages([
                'role' => ['Invalid role provided.'],
            ]);
        }

        $user->assignRole($roleMap[$role]);

        // If company role, require companyName and create Company record
        if ($role === 'company') {
            // If frontend didn't provide company_name, attempt to infer from email domain
            if (empty($companyName)) {
                $companyName = $this->inferCompanyNameFromEmail($user->email);
            }

            if (empty($companyName)) {
                throw ValidationException::withMessages([
                    'company_name' => ['Company name is required for employer signup.'],
                ]);
            }

            $isAvailable = Company::query()->where('name', $companyName)->exists();
            if ($isAvailable) {
                throw new \Exception('Company already exists');
            }

            Company::create([
                'user_id' => $user->id,
                'name' => $companyName,
                'slug' => Str::slug($companyName),
                'official_email' => $user->email,
                'status' => \App\Enums\Status::ACTIVE,
                'is_verified' => 0,
            ]);
        }

        // Save device info
        $this->saveDevice($user);

        // Generate OTP and send email (same pattern as UserService)
        $otpCode = $this->generateOtp($user);
        Mail::to($user->email)->send(new OtpVerification($user, $otpCode));

        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    private function splitName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);

        return [
            $parts[0] ?? '',
            $parts[1] ?? '',
        ];
    }

    /**
     * Try to infer a company name from an email address domain.
     * Returns null if inference is not possible or domain is a generic provider.
     */
    private function inferCompanyNameFromEmail(string $email): ?string
    {
        $pos = strrpos($email, '@');
        if ($pos === false) {
            return null;
        }

        $host = strtolower(substr($email, $pos + 1));
        $host = preg_replace('/^www\./', '', $host);

        // generic public providers to ignore
        $generic = ['gmail', 'yahoo', 'hotmail', 'outlook', 'live', 'aol', 'icloud', 'protonmail', 'mail'];

        $parts = explode('.', $host);
        if (count($parts) < 2) {
            return null;
        }

        // Get candidate SLD
        $tld = array_pop($parts);
        $sld = array_pop($parts);

        // Handle common 2nd-level TLDs like co.uk
        if (strlen($tld) === 2 && in_array($sld, ['co', 'com', 'org', 'net', 'gov', 'edu'], true)) {
            $candidate = array_pop($parts) ?? $sld;
        } else {
            $candidate = $sld;
        }

        if (! $candidate) {
            return null;
        }

        if (in_array($candidate, $generic, true)) {
            return null;
        }

        // Normalize candidate to title-case, replace dashes/underscores
        $name = str_replace(['-', '_'], ' ', $candidate);
        $name = preg_replace('/[^a-z0-9 ]+/i', '', $name);
        $name = trim($name);

        if ($name === '') {
            return null;
        }

        return ucwords($name);
    }

    private function saveDevice($user)
    {
        UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'name' => request()->userAgent(),
            ],
            [
                'last_activity_at' => now(),
            ]
        );
    }

    private function generateOtp(User $user): string
    {
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpToken::create([
            'user_id' => $user->id,
            'hashed_token' => Hash::make($otpCode),
            'expired_at' => now()->addMinutes(10),
        ]);

        return (string) $otpCode;
    }
}
