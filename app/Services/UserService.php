<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\CompanyRegistered;
use App\Mail\UserRegistered;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Enums\Status;
use App\Mail\OtpVerification;
use App\Models\OtpToken;
use App\Models\UserBio;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class UserService implements UserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function create(array $data, array $meta = []): array|Exception
    {
        return DB::transaction(function () use ($data, $meta) {
            $role = $data['role'] ?? null;

            $roleMap = [
                'talent'  => 'talent',
                'company' => 'employer',
            ];

            if (!isset($roleMap[$role])) {
                throw ValidationException::withMessages([
                    'role' => ['Invalid role provided.'],
                ]);
            }

            $user = $this->userRepository->create($data);

            $company = null;
            if ($data['role'] == 'company') {
                $isAvailable = Company::query()->where('name', $data['company_name'])->exists();
                if ($isAvailable) {
                    throw new Exception('Company already exists');
                }

                $company = Company::create([
                    'user_id' => $user->id,
                    'name' => $data['company_name'],
                    'slug' => Str::slug($data['company_name']),
                    'official_email' => $data['email'],
                    'status' => Status::ACTIVE,
                    'is_verified' => 0,
                ]);
            } else {
                $userBio = UserBio::create([
                    'user_id' => $user->id,
                    'current_role',
                    'bio',
                    'track_id',
                    'project_name',
                    'project_url'
                ]);
            }

            $user->assignRole($roleMap[$role]);

            // if ($role == 'company' && $company) {
            //     Mail::to($company->official_email)->send(new CompanyRegistered($user, $company));
            // } else {
            //     Mail::to($user->email)->send(new UserRegistered($user));
            // }

            //? Generate OTP for user and store in otp_tokens
            $otpCode = $this->generateOtp($user);

            //? Send email to user (commented out for now)
            Mail::to($user->email)->send(new OtpVerification($user, $otpCode));

            //? authenticate user after registration
            $credentials = [
                'email' => $data['email'],
                'password' => $data['password']
            ];

            if (!Auth::attempt($credentials)) {
                throw new AuthenticationException(__('auth.failed'));
            }

            $userCredentials = [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ];

            return $userCredentials;
        });
    }

    public function logout(): void
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
    }


    /**
     * Generate a 4-digit OTP, store hashed in DB with 10-min expiry
     */
    private function generateOtp(User $user): string
    {
        //? Generate a secure 6-digit OTP
        $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        //? Store hashed OTP in otp_tokens table
        OtpToken::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'hashed_token' => Hash::make($otpCode),
            'expired_at' => now()->addMinutes(10),
        ]);

        return (string) $otpCode;
    }
}
