<?php

//? API routes for employer functionalities

use App\Http\Controllers\Employer\CompanyController;
use App\Http\Controllers\Employer\JobController;
use Illuminate\Support\Facades\Route;



// API routes for employer functionalities
Route::prefix('api/employer')->group(function () {

    Route::get('/test', function () {
        dd("Employer route reached");
    });

    //? Employer Company Jobs Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('company/{companyId}/jobs')->group(function () {
            Route::controller(JobController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'storePublishJob');
                Route::post('/draft', 'draft');
                Route::get('/{job_id}', 'show');
                Route::put('/{job_id}', 'update');
                Route::delete('/{job_id}', 'destroy');
                Route::post('/{job_id}/restore', 'restore');
                Route::put('/{job_id}/publish', 'publish');
                Route::put('/{job_id}/unpublish', 'unpublish');
                Route::put('/{job_id}/active', 'updateStatus');
            });
        });
    });


    //? Employer Company Routes
    Route::controller(CompanyController::class)->group(function () {
        Route::prefix('company')->group(function () {
            Route::post('company', 'store');
            Route::get('{uuid}', 'show');
            Route::put('{uuid}', 'update');
            Route::put('{uuid}/logo', 'updateLogo');
        });
    });
});
