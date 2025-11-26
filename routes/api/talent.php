<?php

// ? API routes for talent functionalities

use App\Http\Controllers\Talent\JobController;
use App\Http\Controllers\Talent\ProfileController;
use App\Http\Controllers\Talent\ProfileSettingController;
use App\Http\Controllers\Talent\TalentOnboardingController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/talent')->group(function () {
    Route::get('/test', function () {
        dd('Talent route reached');
    });

    Route::middleware('auth:sanctum')->group(function () {

        // ? TALENT PROFILE CONTROLLER AND ROUTES
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'show');
            Route::put('profile/change-password', 'changePassword');
            Route::put('profile/photo', 'updatePhoto');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
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
            });
        });
    });


    Route::middleware('auth:sanctum')->prefix('settings')->group(function () {
        // Talent Onboarding
        Route::controller(ProfileSettingController::class)->group(function () {
            Route::get('profile', 'index');
            Route::post('profile', 'store');
            Route::post('skills', 'skill');
            Route::post('profile', 'store');
        });
    });    
});
