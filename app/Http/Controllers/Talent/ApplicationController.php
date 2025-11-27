<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\StoreApplicationRequest;
use App\Http\Resources\Talent\ApplicationResource;
use App\Services\Interfaces\Talent\ApplicationServiceInterface;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct(
        private readonly ApplicationServiceInterface $applicationService,
    ) {}


    public function index(Request $request)
    {
        // dd($request->all());
        $response = $this->applicationService->listApplications();
        // dd($response);

        if ($response->success) {
            $applications = ApplicationResource::collection($response->applications);

            return $this->successWithData(
                $applications,
                $response->message,
                $response->status,
            );
        }

        // return error message
        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function store(StoreApplicationRequest $request)
    {
        // dd($request->all());
        $response = $this->applicationService->createApplication(
            $request->all(),
            $request
        );
        // dd($response);

        if ($response->success) {
            return $this->successWithData(
                new ApplicationResource($response->application),
                $response->message,
                $response->status,
            );
        }

        // return error message
        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function show(Request $request, string $applicationId)
    {
        // dd($request->all());
        $response = $this->applicationService->getSingleApplication($applicationId);
        // dd($response);

        if ($response->success) {
            return $this->successWithData(
                new ApplicationResource($response->application),
                $response->message,
                $response->status,
            );
        }

        // return error message
        return $this->error(
            $response->message,
            $response->status,
        );
    }

    public function withdraw(Request $request, string $applicationId)
    {
        // dd($request->all());
        $response = $this->applicationService->withdrawApplication($applicationId);
        // dd($response);

        if ($response->success) {
            return $this->successWithData(
                new ApplicationResource($response->application),
                $response->message,
                $response->status,
            );
        }

        // return error message
        return $this->error(
            $response->message,
            $response->status,
        );
    }
}