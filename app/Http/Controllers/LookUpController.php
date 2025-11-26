<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\JobLevelResource;
use App\Http\Resources\JobTypeResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\TrackResource;
use App\Http\Resources\WorkModeResource;
use App\Services\Admin\CategoryService;
use App\Services\Admin\CountryService;
use App\Services\Admin\JobLevelService;
use App\Services\Admin\LocationService;
use App\Services\Admin\StateService;
use App\Services\Admin\TrackService;
use App\Services\Admin\WorkModeService;
use App\Services\JobTypeService;
use App\Services\SkillService;

class LookUpController extends Controller
{
    public function __construct(
        private readonly CountryService $countryService,
        private readonly LocationService $locationService,
        private readonly JobTypeService $jobTypeService,
        private readonly SkillService $skillService,
        private readonly TrackService $trackService,
        private readonly WorkModeService $workModeService,
        private readonly CategoryService $categoryService,
        private readonly StateService $stateService,
        private readonly JobLevelService $joblevelService,
    ) {}

    public function countries()
    {
        $response = $this->countryService->getAllCountries();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        $countries = CountryResource::collection($response->data);
        return $this->successWithData(
            $countries,
            $response->message,
            $response->status,
        );
    }

    public function tracks()
    {
        $response = $this->trackService->getAll();
        if ($response->success === false) {
            return $this->error($response->message, $response->code);
        }

        $tracks = TrackResource::collection($response->data);
        return $this->successWithData(
            $tracks,
            $response->message,
            $response->status,
        );
    }

    public function states()
    {
        $response = $this->stateService->getAll();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        $states = StateResource::collection($response->data);
        return $this->successWithData(
            $states,
            $response->message,
            $response->status,
        );
    }

    public function skills()
    {
        $data = $this->skillService->getAllSkills();

        // dd($data);
        $skils = SkillResource::collection($data);
        return $this->successWithData($skils, 'Skills retrieved successfully');
    }

    public function workModes()
    {
        $response = $this->workModeService->getAll();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        $workmodes = WorkModeResource::collection($response->data);
        return $this->successWithData(
            $workmodes,
            $response->message,
            $response->status,
        );
    }

    public function categories()
    {
        $response = $this->categoryService->getAllCategories();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        $categories = CategoryResource::collection($response->data);
        return $this->successWithData(
            $categories,
            $response->message,
            $response->status,
        );
    }

    public function jobTypes()
    {
        $data = $this->jobTypeService->getAllJobTypes();

        // dd($data);
        $categories = JobTypeResource::collection($data);
        return $this->successWithData($categories, 'Job types retrieved successfully');
    }


    public function jobLevels()
    {
        $response = $this->joblevelService->getAllJobLevels();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        $jobLevels = JobLevelResource::collection($response->data);
        return $this->successWithData(
            $jobLevels,
            $response->message,
            $response->status,
        );
    }
}