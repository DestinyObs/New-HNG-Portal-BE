<?php

namespace App\Http\Controllers\Talent;

use App\Enums\Http;
use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\WorkExperienceProfileSettingRequest;
use App\Models\TalentWorkExperience;
use Illuminate\Http\Request;

class ExperienceSettingController extends Controller
{

    // // THIS METHODS NEEDS TO BE MODIFIED INTO SERVICE CLASS

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = $request->user();
        $experience = TalentWorkExperience::where('user_id', $user->id)->get();
        if ($experience->isEmpty()) {
            return $this->notFound('Not found, No experience added yet!');
        }

        return $this->successWithData($experience, 'User experiences retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkExperienceProfileSettingRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $experience = $user->experiences()->create($data);
        return $this->successWithData($experience, 'created', Http::CREATED); // 201

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $experience = TalentWorkExperience::where('id', $id)->firstOrFail();
        if (!$experience) {
            return $this->notFound('Not found!');
        }

        return $this->successWithData($experience, 'User experience retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkExperienceProfileSettingRequest $request, string $id)
    {
        $experience = TalentWorkExperience::where('id', $id)->firstOrFail();
        if (!$experience) {
            return $this->notFound('No experience added yet!');
        }
        $data = $request->validated();
        $experience->update($data);
        return $this->successWithData($experience, 'User experience updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $experience = TalentWorkExperience::where('id', $id)->firstOrFail();
        if (!$experience) {
            return $this->notFound('No experience added yet!');
        }
        $experience->delete();
        return $this->noContent();
    }
}
