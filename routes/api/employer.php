<?php

//? API routes for employer functionalities

use App\Http\Controllers\Employer\CompanyController;
use App\Http\Controllers\Employer\JobController;
use Illuminate\Support\Facades\Route;

Route::prefix('employer')->middleware('auth:sanctum')->group(function () {
    Route::get('/test', function () {
        dd("Employer route reached");
    });

    Route::post('company', [CompanyController::class, 'store']);
    Route::get('company/{uuid}', [CompanyController::class, 'show']);
    Route::put('company/{uuid}', [CompanyController::class, 'update']);
    Route::put('company/{uuid}/logo', [CompanyController::class, 'updateLogo']);

    // Nested job routes
    Route::prefix('company/{uuid}/jobs')->group(function () {
        Route::get('', [JobController::class, 'index']);
        Route::post('', [JobController::class, 'store']);
        Route::get('{job_id}', [JobController::class, 'show']);
        Route::put('{job_id}', [JobController::class, 'update']);
        Route::delete('{job_id}', [JobController::class, 'destroy']);
        Route::put('{job_id}/restore', [JobController::class, 'restore']);
        Route::put('{job_id}/publish', [JobController::class, 'publish']);
        Route::put('{job_id}/unpublish', [JobController::class, 'unpublish']);
    });
});
