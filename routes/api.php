<?php


use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\WaitlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OtpTokenController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\JobTypesController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LookUpController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\WorkModeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store']);
    Route::post('reset-password', [ForgotPasswordController::class, 'update']);
    Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/register', action: [UserController::class, 'store']);
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/waitlist/{waitlist}', [WaitlistController::class, 'show']);

// Route::post('verify-otp', [])
Route::controller(OtpTokenController::class)->prefix('otp')->group(function () {
    Route::post('verify-otp', 'verifyOtp')->middleware('auth:sanctum');
    Route::post('resend-otp', 'resendOtp')->middleware('auth:sanctum');
});


//? LookUp controllers for forms, filters and dropdowns;
Route::controller(LookUpController::class)->group(function () {
    Route::prefix('lookups')->group(function () {
        Route::get('countries', 'countries');
        // Route::get('locations', 'getLocations');
        Route::get('job-types', 'jobTypes');
        Route::get('skills', 'skills');
        Route::get('tracks', 'tracks');
        Route::get('categories', 'getCategories');
        Route::get('work-modes', 'workModes');
        Route::get('states', 'states');
        Route::get('categories', 'categories');
    });
});
