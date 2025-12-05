<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\CompanyProfileSettingRequest;
use App\Http\Requests\Talent\UpdatePasswordRequest;
use App\Models\Company;
use App\Services\Employer\CompanyService;
use App\Services\Interfaces\Talent\ProfileServiceInterface;
use Illuminate\Http\Request;

class CompanyProfileSettingController extends Controller
{
    public function __construct(
        private CompanyService $companyService,
        private readonly ProfileServiceInterface $profileService
    ) {}

    /**
     * Display a listing of the resource.
     */
    // // THIS METHODS NEEDS TO BE MODIFIED INTO SERVICE CLASS
    public function profile(Request $request)
    {
        $user = $request->user();
        $company = Company::where('user_id', $user->id)->firstOrFail();
        return $this->successWithData($company, 'Company profile retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyProfileSettingRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $company = Company::where('user_id', $user->id)->firstOrFail();

        if ($request->hasFile('logo')) {
            $company->media->each->delete();
            $url = $company->addMediaFromRequest('logo')->toMediaCollection('logo');
            // Update model with profile image url - optional
            $data['logo_url'] = $url?->original_url;
            // Update model with profile image url - optional
            $user->update([
                'photo_url' => $url?->original_url
            ]);
        }

        // $company->update($data);
        $company = $this->companyService->updateCompany($data, $company->id);

        return $this->successWithData($company, 'Company updated successfully');
    }

    /**
     * Change company password
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
}
