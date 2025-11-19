<?php

//? API routes for employer functionalities

use App\Http\Controllers\Employer\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/employer')->group(function () {
    Route::get('/test', function () {
        dd("Employer route reached");
    });

    Route::post('company', [CompanyController::class, 'store']);
    Route::get('company/{uuid}', [CompanyController::class, 'show']);
    Route::put('company/{uuid}', [CompanyController::class, 'update']);
    Route::put('company/{uuid}/logo', [CompanyController::class, 'updateLogo']);
});
