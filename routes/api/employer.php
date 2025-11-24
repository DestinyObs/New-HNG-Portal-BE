<?php

//? API routes for employer functionalities

use App\Http\Controllers\Employer\CompanyController;
use App\Http\Controllers\Employer\CompanyOnboardingController;
use App\Http\Controllers\Employer\JobController;
use Illuminate\Support\Facades\Route;



// API routes for employer functionalities
Route::prefix('api/employer')->group(function () {

    Route::get('/test', function () {
        dd("Employer route reached");
    });

    //? Employer Company Jobs Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('company/{companyId}/jobs')->group(function () {
            Route::controller(JobController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'storePublishJob');
                Route::post('/draft', 'draft');
                Route::get('/{job_id}', 'show');
                Route::put('/{job_id}', 'update');
                Route::delete('/{job_id}', 'destroy');
                Route::post('/{job_id}/restore', 'restore');
                Route::put('/{job_id}/publish', 'publish');
                Route::put('/{job_id}/unpublish', 'unpublish');
                Route::put('/{job_id}/active', 'updateStatusToActive');
                Route::put('/{job_id}/inactive', 'updateStatusToInActive');
            });
        });

        //? Employer Company Routes
        Route::controller(CompanyController::class)->group(function () {
            Route::prefix('company')->group(function () {
                Route::post('company', 'store');
                Route::get('{companyId}', 'show');
                Route::put('{companyId}', 'update');
                Route::put('{companyId}/logo', 'updateLogo');
            });
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        // Talent Onboarding
        Route::controller(CompanyOnboardingController::class)->group(function () {
            Route::get('onboarding', 'index');
            Route::post('onboarding', 'store');
        });
    });      

});
