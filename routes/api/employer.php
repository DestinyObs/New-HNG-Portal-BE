<?php

//? API routes for employer functionalities

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employer\CompanyController;

Route::prefix('api/employer')->group(function () {
    Route::get('/test', function () {
        dd("Employer route reached");

        //company job application routes
         Route::prefix('company/{companyUuid}/jobs/{jobUuid}')->group(function () {
        Route::get('/applications', [CompanyController::class, 'getJobApplicants']);
    });
    });
});
