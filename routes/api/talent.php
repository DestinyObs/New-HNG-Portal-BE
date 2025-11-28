<?php

// ? API routes for talent functionalities

use App\Http\Controllers\Talent\DashboardController;
use App\Http\Controllers\Talent\ExperienceSettingController;
use App\Http\Controllers\Talent\ApplicationController;
use App\Http\Controllers\Talent\JobController;
use App\Http\Controllers\Talent\PortfolioController;
use App\Http\Controllers\Talent\ProfileController;
use App\Http\Controllers\Talent\ProfileSettingController;
use App\Http\Controllers\Talent\TalentOnboardingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('api/talent')->group(function () {
    Route::get('/test', function () {
        dd('Talent route reached');
    });
  
    // TALENT DASHBOARD
    Route::get('dashboard/analysis', [DashboardController::class, 'analysis']);
    Route::get('dashboard/recommended', [DashboardController::class, 'recommendedJobs']);

    // ? TALENT PROFILE CONTROLLER AND ROUTES
    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'show');
        Route::put('profile/change-password', 'changePassword');
        Route::put('profile/photo', 'updatePhoto');
    });

    // Talent Onboarding
    Route::controller(TalentOnboardingController::class)->group(function () {
        Route::get('onboarding', 'index');
        Route::post('onboarding', 'store');
    });

    // Talent JobController
    Route::controller(JobController::class)->group(function () {
        Route::prefix('jobs')->group(function () {
            Route::get('/', 'index');
            Route::get('/bookmark', 'getSaveJobs');
            Route::get('/{jobId}', 'show');
            Route::put('/{jobId}/bookmark', 'saveJob');
            Route::get('/company/{companyId}', "viewCompanyProfile");
        });
    });

    // Talent Applications
    Route::controller(ApplicationController::class)->group(function () {
        Route::prefix('applications')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/view/{applicationId}', 'show');
            Route::put('/withdraw/{applicationId}', 'withdraw');
        });
    });

    // TALENT Profile Settings
    Route::prefix('settings')->group(function () {
        Route::controller(ProfileSettingController::class)->group(function () {
            Route::get('profile', 'index');
            Route::post('profile', 'store');
            Route::post('skills', 'skill');
            Route::post('profile', 'store');
        });

        // TALENT Experiences
        Route::apiResource('work-experiences', ExperienceSettingController::class);
    });
});
