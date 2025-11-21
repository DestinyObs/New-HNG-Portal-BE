<?php

use Illuminate\Support\Facades\Route;



// API routes for employer functionalities
Route::prefix('employer')->group(function () {

    Route::get('/test', function () {
        dd("Employer route reached");
    });

    // Company Jobs Routes
    Route::prefix('company/{uuid}/jobs')->group(function () {

        Route::get('/', [\App\Http\Controllers\Employer\JobController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Employer\JobController::class, 'store']);
        Route::get('/{job_id}', [\App\Http\Controllers\Employer\JobController::class, 'show']);
        Route::put('/{job_id}', [\App\Http\Controllers\Employer\JobController::class, 'update']);
        Route::delete('/{job_id}', [\App\Http\Controllers\Employer\JobController::class, 'destroy']);
        Route::post('/{job_id}/restore', [\App\Http\Controllers\Employer\JobController::class, 'restore']);
        Route::put('/{job_id}/publish', [\App\Http\Controllers\Employer\JobController::class, 'publish']);
        Route::put('/{job_id}/unpublish', [\App\Http\Controllers\Employer\JobController::class, 'unpublish']);

    });

});
