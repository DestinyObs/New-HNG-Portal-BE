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
        return $this->processGoogleUser($googleUser, 'talent');
    }

    public function handleToken(string $accessToken, ?string $role = null, ?string $companyName = null): array
    {
        $googleUser = Socialite::driver('google')->stateless()->userFromToken($accessToken);

        return $this->processGoogleUser($googleUser, $role, $companyName);
    }

    private function processGoogleUser(GoogleUser $googleUser, ?string $role = null, ?string $companyName = null): array
    {
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            return $this->loginExistingUser($user, $role, $googleUser);
        }

        return $this->registerNewUser($googleUser, $role, $companyName);
    }

    private function loginExistingUser(User $user, ?string $role, GoogleUser $googleUser): array
    {
        if ($role) {
            $this->validateRoleForExistingUser($user, $role, $googleUser->getEmail());
        }

        $this->saveDevice($user);
        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    private function registerNewUser(GoogleUser $googleUser, ?string $role, ?string $companyName): array
    {
        $this->validateRoleForNewUser($role, $googleUser->getEmail());

        [$firstname, $lastname] = $this->splitName($googleUser->getName());
        $password = Str::random(12);

        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $googleUser->getEmail(),
            'password' => $password,
        ]);

        $roleMap = ['talent' => 'talent', 'company' => 'employer', 'admin' => 'admin'];
        $user->assignRole($roleMap[$role]);

        if ($role === 'company') {
            $this->createCompanyForUser($user, $companyName);
        }

        $this->saveDevice($user);
        $otpCode = $this->generateOtp($user);
        Mail::to($user->email)->send(new OtpVerification($user, $otpCode));

        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    private function validateRoleForExistingUser(User $user, string $role, string $email): void
    {
        $roleMap = ['talent' => 'talent', 'company' => 'employer', 'admin' => 'admin'];

        if (! isset($roleMap[$role])) {
            Log::warning('Google auth attempted with invalid role for existing user', [
                'role' => $role,
                'email' => $email,
            ]);
            throw ValidationException::withMessages(['role' => ['Invalid role provided.']]);
        }

        $expectedInternal = $roleMap[$role];
        if (! $user->hasRole($expectedInternal)) {
            throw ValidationException::withMessages([
                'role' => ["User already exists with a different role and cannot sign up as '{$role}'."],
            ]);
        }
    }

    private function validateRoleForNewUser(?string $role, string $email): void
    {
        $roleMap = ['talent' => 'talent', 'company' => 'employer', 'admin' => 'admin'];

        if (! $role) {
            throw ValidationException::withMessages([
                'role' => ['Role is required for Google signup.'],
            ]);
        }

        if (! isset($roleMap[$role])) {
            Log::warning('Google signup attempted with invalid role', [
                'role' => $role,
                'email' => $email,
            ]);
            throw ValidationException::withMessages(['role' => ['Invalid role provided.']]);
        }
    }

    private function createCompanyForUser(User $user, ?string $companyName): void
    {
        if (empty($companyName)) {
            $companyName = $this->inferCompanyNameFromEmail($user->email);
        }

        if (empty($companyName)) {
            throw ValidationException::withMessages([
                'company_name' => ['Company name is required for employer signup.'],
            ]);
        }

        $companyExists = Company::query()->where('name', $companyName)->exists();
        if ($companyExists) {
            throw ValidationException::withMessages([
                'company_name' => ['A company with this name already exists.'],
            ]);
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

    private function splitName(string $fullName): array
    {
        $nameParts = explode(' ', $fullName, 2);

        return [
            $nameParts[0] ?? '',
            $nameParts[1] ?? '',
        ];
    }

    private function inferCompanyNameFromEmail(string $email): ?string
    {
        $pos = strrpos($email, '@');
        if ($pos === false) {
            return null;
        }

        $host = strtolower(substr($email, $pos + 1));
        $host = preg_replace('/^www\./', '', $host);

        $generic = ['gmail', 'yahoo', 'hotmail', 'outlook', 'live', 'aol', 'icloud', 'protonmail', 'mail'];

        $parts = explode('.', $host);
        if (count($parts) < 2) {
            return null;
        }

        $tld = array_pop($parts);
        $sld = array_pop($parts);

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
