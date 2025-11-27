<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\TalentOnboardingRequest;
use App\Models\UserBio;
use Illuminate\Http\Request;

class TalentOnboardingController extends Controller
{
    // // THIS METHODS NEEDS TO BE MODIFIED INTO SERVICE CLASS
    public function index(Request $request)
    {
        $user = $request->user();
        $user_bio = UserBio::where('user_id', $user->id)->first();
        $user_bio->load('user');
        $user_bio->getMedia();

        return $this->successWithData($user_bio, 'User bio retrieved successfully');
    }

    public function store(TalentOnboardingRequest $request)
    {

        $data = $request->validated();
        $user = $request->user();
        $user_bio = UserBio::where('user_id', $user->id)->first();

        if ($request->hasFile('profile_image')) {
            $user_bio->media->each->delete();
            $url = $user_bio->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
            // Update model with profile image url - optional
            $user->update([
                'photo_url' => $url?->original_urls
            ]);
        }

        if ($request->hasFile('project_file')) {
            $user_bio->media->each->delete();
            $url = $user_bio->addMediaFromRequest('project_file')->toMediaCollection('project_file');
            // Update model with project file url - optional
            $user_bio['project_file_url'] = $url?->original_url;
        }
        // $user_bio->update($data);
        // $user_bio = $this->userBioService->updateUserBio($data, $user_bio->id);
        $user_bio->update($data);

        return $this->successWithData($user_bio, 'User bio updated successfully');
    }
}
