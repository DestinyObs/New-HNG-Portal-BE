<?php

namespace App\Http\Controllers;

use App\Services\Admin\CategoryService;
use App\Services\Admin\CountryService;
use App\Services\Admin\LocationService;
use App\Services\Admin\StateService;
use App\Services\Admin\TrackService;
use App\Services\Admin\WorkModeService;
use App\Services\JobTypeService;
use App\Services\SkillService;
use Illuminate\Http\Request;

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
    ) {}


    public function countries()
    {
        $response = $this->countryService->getAllCountries();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        return $this->successWithData(
            $response->data,
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

        return $this->successWithData(
            $response->data,
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

        return $this->successWithData(
            $response->data,
            $response->message,
            $response->status,
        );
    }

    public function skills()
    {
        $data =  $this->skillService->getAllSkills();

        // dd($data);
        return $this->successWithData($data, 'Skills retrieved successfully');
    }

    public function workModes()
    {
        $response = $this->workModeService->getAll();
        if ($response->success === false) {
            return $this->error($response->message, $response->status);
        }

        return $this->successWithData(
            $response->data,
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

        return $this->successWithData(
            $response->data,
            $response->message,
            $response->status,
        );
    }

    public function jobTypes()
    {
        $data = $this->jobTypeService->getAllJobTypes();
        // dd($data);
        return $this->successWithData($data, 'Job types retrieved successfully');
    }
}