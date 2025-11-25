<?php

declare(strict_types=1);

namespace App\Services\Talent;

use App\Enums\Http;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\Talent\ProfileRepositoryInterface;
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use App\Traits\UploadFile;
use Illuminate\Http\Request;

class ProfileService implements ProfileServiceInterface
{
    use UploadFile;

    public function __construct(
        private readonly ProfileRepositoryInterface $profileRepository
    ) {
    }

    public function changePassword(User $user, string $newPassword): object|array
    {
        try {
            $user = $this->profileRepository->updatePassword($user, $newPassword);

            logger()->info("Password updated successfully for user ID {$user->id}");

            $user->load([
                'company',
                'bio',
                'skills',
                'experiences',
                'verification',
                'preferences',
                'jobs',
            ]);

            return (object) [
                'success' => true,
                'message' => 'Password updated successfully',
                'user' => new UserResource($user),
                'status' => Http::OK,
            ];
        } catch (\Exception $e) {
            logger()->error("Failed to update password for user ID {$user->id}: " . $e->getMessage());

            return (object) [
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage(),
                'status' => Http::INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function updateProfilePhoto(User $user, Request $request): object|array
    {
        try {
            // ? check if photo exist in request file
            if (!$request->hasFile('photo')) {
                return (object) [
                    'success' => false,
                    'message' => 'No photo file provided',
                    'status' => Http::BAD_REQUEST,
                ];
            }

            // ? if exist update
            $filePath = $this->uploadFile(
                $request->file('photo'),
                'talent/profile_photos',
                $user->photo_url
            );

            // ? update profile photo in database
            $user = $this->profileRepository->updateProfilePhoto(
                $user,
                $filePath,
            );

            // ? return success response
        } catch (\Exception $e) {
        }
    }
}