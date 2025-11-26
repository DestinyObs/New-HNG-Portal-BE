<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\ProfileSettingRequest;
use App\Http\Requests\Talent\SkillProfileSettingRequest;
use App\Http\Requests\Talent\WorkExperienceProfileSettingRequest;
use App\Models\UserBio;
use Illuminate\Http\Request;

class ProfileSettingController extends Controller
{
    // // THIS METHODS NEEDS TO BE MODIFIED INTO SERVICE CLASS
    public function index(Request $request)
    {
        $user = $request->user();
        $user_bio = UserBio::where('user_id', $user->id)->first();
        $user_bio->load('user', 'user.skills');
        $user_bio->getMedia();

        return $this->successWithData($user_bio, 'User profile retrieved successfully');
    }

    public function skill(SkillProfileSettingRequest $request)
    {
        $skill_ids = $request->validated();
        $user = $request->user();
        $user->userSkills()->sync($skill_ids['skill_ids']);

        return $this->successWithData($user->skills, 'User skills updated successfully');
    }

    public function experience(WorkExperienceProfileSettingRequest $request)
    {
        return $request;
    }

    public function store(ProfileSettingRequest $request)
    {
        // return $request;

        $data = $request->validated();
        $user = $request->user();
        $user_bio = UserBio::where('user_id', $user->id)->first();

        if ($request->hasFile('profile_image')) {
            $user_bio->media->each->delete();
            $user_bio->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
        }

        $user_bio->update($data);

        return $this->successWithData($user_bio, 'User profile updated successfully');
    }


}
