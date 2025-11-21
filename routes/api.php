<?php

use App\Http\Controllers\Admin\JobTypeController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpTokenController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\LookUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\SkillController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'Up and Active!',
        'timestamp' => now()
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store']);
    Route::post('reset-password', [ForgotPasswordController::class, 'update']);
    Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/register', action: [UserController::class, 'store']);
    Route::post('verify-otp', [OtpTokenController::class, 'verify']);
    Route::post('resend-otp', [OtpTokenController::class, 'resend']);
});
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/waitlist/{waitlist}', [WaitlistController::class, 'show']);

// LOOKUPS ROUTES
Route::prefix('lookups')->group(function () {
    Route::get('countries', [LookUpController::class, 'countries']);
    Route::get('states', [LookUpController::class, 'states']);
    Route::get('job-types', [LookUpController::class, 'jobTypes']);
    Route::get('skills', [LookUpController::class, 'skills']);
    Route::get('tracks', [LookUpController::class, 'tracks']);
    Route::get('categories', [LookUpController::class, 'categories']);
    Route::get('work-modes', [LookUpController::class, 'workModes']);
});

// USERS ROUTES
Route::apiResource('users', UserController::class)
    ->only(['index', 'store', 'show']);

// SKILLS ROUTES
Route::apiResource('skills', SkillController::class);

// JOB TYPES ROUTES
Route::apiResource('job-types', JobTypeController::class)
    ->only(['index', 'show']);

// Include sub-route files
require __DIR__ . '/api/admin.php';
require __DIR__ . '/api/employer.php';
require __DIR__ . '/api/talent.php';


