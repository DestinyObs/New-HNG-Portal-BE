<?php

use App\Http\Controllers\Admin\JobTypeController;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\WaitlistController;
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
