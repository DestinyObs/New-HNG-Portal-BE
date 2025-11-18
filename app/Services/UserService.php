<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Enums\Status;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class UserService implements UserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}
     
public function create(array $data, array $meta = []): User
{
    return DB::transaction(function () use ($data, $meta) {

        // Validate role input
        $incomingRole = $data['role'] ?? null;

        $roleMap = [
            'talent'  => 'talent',
            'company' => 'employer',
        ];

        if (!isset($roleMap[$incomingRole])) {
            throw ValidationException::withMessages([
                'role' => ['Invalid role provided.'],
            ]);
        }
        
        unset($data['role']);

        // Create the user
        $user = $this->userRepository->create($data);

        // Assign Spatie role
        $user->assignRole($roleMap[$incomingRole]);

        // Send registration email
        Mail::to($user->email)->send(new \App\Mail\UserRegistered($user));

        return $user;
    });
}


}
