<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\CompanyOnboardingRequest;
use App\Models\Company;
use App\Services\Employer\CompanyService;
use Illuminate\Http\Request;

class CompanyOnboardingController extends Controller
{
    public function __construct(private CompanyService $companyService) {}

    // THIS METHODS NEEDS TO BE MODIFIED INTO SERVICE CLASS
    public function index(Request $request)
    {
        $user = $request->user();
        $company = Company::where('user_id', $user->id)->first();
        $company->load('user');
        $company->getMedia();

        return $this->successWithData($company, 'Company retrieved successfully');
    }

    public function store(CompanyOnboardingRequest $request)
    {

        $data = $request->validated();
        $user = $request->user();
        $company = Company::where('user_id', $user->id)->first();

        if ($request->hasFile('logo')) {
            $company->media->each->delete();
            $company->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        // $company->update($data);
        return $company = $this->companyService->updateCompany($data, $company->id);

        return $this->successWithData($company, 'Company updated successfully');
    }


    // implement onboarding status
    public function onBoardingStatus(array $data){
        $status = false;
        foreach($data as $record){
            if($record == null){
                $status = false;
            }else{
                $status = true;
            }
        }
    }
}
