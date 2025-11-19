<?php

use App\Http\Controllers\Admin\JobTypeController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\{
    CategoryController,
    CountryController,
    JobTypesController,
    LocationController,
    SkillController,
    StateController,
    TagController,
    TrackController,
    UserController,
    WaitlistController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;


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

// JOB TYPES ROUTES
Route::apiResource('job-types', JobTypeController::class)
    ->only(['index', 'show']);

// Countries & States
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{country}', [CountryController::class, 'show']);
Route::get('/countries/{country}/states', [StateController::class, 'getByCountry']);
Route::get('/states', [StateController::class, 'index']);
Route::get('/states/{state}', [StateController::class, 'show']);

// Tracks
Route::get('/tracks', [TrackController::class, 'index']);
Route::get('/tracks/{track}', [TrackController::class, 'show']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

// Locations
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{location}', [LocationController::class, 'show']);

// Job Types
Route::get('/job-types', [JobTypesController::class, 'index']);
Route::get('/job-types/{jobType}', [JobTypesController::class, 'show']);

// Tags
Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{tag}', [TagController::class, 'show']);

// Skills
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/{skill}', [SkillController::class, 'show']);
