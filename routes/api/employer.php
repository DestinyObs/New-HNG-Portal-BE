<?php

// ? API routes for employer functionalities

use App\Http\Controllers\Employer\CompanyController;
use App\Http\Controllers\Employer\CompanyOnboardingController;
use App\Http\Controllers\Employer\DashboardController;
use App\Http\Controllers\Employer\JobController;
use Illuminate\Support\Facades\Route;


// API routes for employer functionalities
Route::middleware(['auth:sanctum', 'role:employer'])->prefix('api/employer')->group(function () {

    Route::get('/test', function () {
        dd('Employer route reached');
    });

    Route::middleware(['auth:sanctum'])->get('dashboard', [DashboardController::class, 'index']);

    // ? Employer Company Jobs Routes
    Route::prefix('company/{companyId}')->middleware('company.owner')->group(function () {
        Route::prefix('jobs')->group(function () {
            Route::controller(JobController::class)->group(function () {

                // Jobs
                Route::get('/', 'index');
                Route::post('/store', 'storePublishJob');
                Route::post('/draft', 'draft');
                Route::get('/draft', 'listDraftedJobs');

                Route::prefix('{job_id}')->middleware('company.job')->group(function () {
                    Route::get('/', 'show');
                    Route::put('/', 'update');
                    Route::delete('/', 'destroy');
                    Route::post('restore', 'restore');

                    // Job Status
                    Route::put('publish', 'publish');
                    Route::put('unpublish', 'unpublish');
                    Route::put('active', 'updateStatusToActive');
                    Route::put('inactive', 'updateStatusToInActive');

                    // Job Application
                    Route::get('applications', 'applications');

                    Route::prefix('applications/{applicationId}')->group(function () {
                        Route::get('', 'viewSingleApplication');

                        // Update application status
                        Route::put('/status/{status}', 'updateApplicationStatus')
                            ->whereIn('status', ['pending', 'accepted', 'rejected']);
                    });
                });
            });
        });
    });

    // ? Employer Company Routes
    Route::controller(CompanyController::class)->group(function () {
        Route::prefix('company')->group(function () {
            Route::post('company', 'store');
            Route::middleware('company.owner')->group(function () {
                Route::get('{companyId}', 'show');
                Route::put('{companyId}', 'update');
                Route::put('{companyId}/logo', 'updateLogo');
                Route::get('{companyId}/applications', 'applications');
            });
        });
    });

    // Company Onboarding
    Route::controller(CompanyOnboardingController::class)->group(function () {
        Route::get('onboarding', 'index');
        Route::post('onboarding', 'store');
    });
});
