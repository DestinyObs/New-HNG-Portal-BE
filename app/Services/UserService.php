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
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class UserService implements UserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

public function create(array $data, array $meta = []): User|Exception
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
        }

        $user->assignRole($roleMap[$role]);

        if ($role == 'company' && $company) {
            Mail::to($company->official_email)->send(new CompanyRegistered($user, $company));
        } else {
            Mail::to($user->email)->send(new UserRegistered($user));
        }

        return $user;
    });
}

    public function logout(): void
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
    }

}
