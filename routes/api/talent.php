<?php

// ? API routes for talent functionalities

use App\Http\Controllers\Talent\ExperienceSettingController;
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
    });


    Route::middleware('auth:sanctum')->prefix('settings')->group(function () {
        // TALENT Profile Settings
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
