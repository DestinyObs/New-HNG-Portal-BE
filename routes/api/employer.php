<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employer\CompanyController;

Route::prefix('employer')->group(function () {

    Route::get('/test', function () {
        return "Employer route reached";
    });

    // company job application routes
    Route::prefix('company/{companyUuid}/jobs/{jobUuid}')->group(function () {
        Route::get('/applications', [CompanyController::class, 'getJobApplicants']);
    });

    Route::prefix('company/{companyUuid}/applications/{applicationUuid}')->group(function () {
        Route::get('/', [CompanyController::class, 'getApplication']);
        Route::put('/status', [CompanyController::class, 'updateApplicationStatus']);
    });

    // search talents route
    Route::get('/company/{companyUuid}/talents', [CompanyController::class, 'searchTalents']);

});
