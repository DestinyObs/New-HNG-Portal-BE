<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\ProfileSettingRequest;
use App\Http\Requests\Talent\SkillProfileSettingRequest;
use App\Http\Requests\Talent\WorkExperienceProfileSettingRequest;
use App\Models\UserBio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileSettingController extends Controller
{
    // // THIS METHODS NEEDS TO BE MODIFIED INTO SERVICE CLASS
    public function index(Request $request)
    {
        $user = $request->user();
        $user->load('bio', 'skills', 'experiences', 'portfolios');
        // $user_bio = UserBio::where('user_id', $user->id)->firstOrFail();
        // $user_bio->load('user', 'user.skills', 'user.experiences', 'user.portfolios');
        // $user_bio->getMedia();

        return $this->successWithData($user, 'User profile retrieved successfully');
    }


    public function getSkills(Request $request)
    {
        $user = $request->user();

        $user_skills = $user->skills;

        return $this->successWithData($user_skills, 'User skills retrieved successfully');
    }

    public function skill(SkillProfileSettingRequest $request)
    {
        $skill_ids = $request->validated();
        $user = $request->user();

        // There was a sync error due to uuid on the user skill table
        // Format the input array to include a generated ID for the pivot table
        $skillsToSync = [];
        foreach ($skill_ids['skill_ids'] as $skillId) {
            // Use Str::uuid() or Str::ulid()
            $skillsToSync[$skillId] = ['id' => Str::uuid()];
        }
        // {"019ac063-f524-7049-8a89-e7bffdc97023": {"id": "9aa181fe-36ed-4fdc-ac76-3e4dcee19239"}}
        $user->skills()->sync($skillsToSync);

        // $user->userSkills()->sync($skill_ids['skill_ids']);

        return $this->successWithData($user->skills, 'User skills updated successfully');
    }


    public function store(ProfileSettingRequest $request)
    {

        $data = $request->validated();
        $user = $request->user();
        $user_bio = UserBio::where('user_id', $user->id)->firstOrFail();

        if ($request->hasFile('profile_image')) {
            $user->media->each->delete();
            $url = $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');

            // Update model with profile image url - optional
            $user->update([
                'photo_url' => $url?->original_url
            ]);
        }

        $user_bio->update($data);

        return $this->successWithData($user_bio, 'User profile updated successfully');
    }

    public function update(Request $request)
    {

        $data = $request->validate([
            "firstname" => ['nullable', 'string'],
            "lastname" => ['nullable', 'string'],
            'othername' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'current_role' => ['nullable', 'string'],
            'photo_url' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        $user->update($data);

        return $this->successWithData($user, 'User info updated successfully');
    }
}
