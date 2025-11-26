<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\UpdatePasswordRequest;
use App\Http\Requests\Talent\UpdatePhotoRequest;
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileServiceInterface $profileService
    ) {}

    public function show(Request $request)
    {
        return $this->successWithData(
            $request->user(),
            'Talent profile retrieved successfully.',
        );
    }

    /**
     * Change talent password
     */
    public function changePassword(UpdatePasswordRequest $request)
    {
        // dd($request->all());
        $response = $this->profileService->changePassword(
            $request->user(),
            $request->validated('password'),
        );

        // ? check if success is true
        if ($response->success) {
            return $this->successWithData(
                $response->user,
                $response->message,
            );
        }

        // ? error message should be returned
        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function updatePhoto(UpdatePhotoRequest $request)
    {
        dd($request->all());
    }
}
