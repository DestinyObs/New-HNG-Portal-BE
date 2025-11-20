<?php

use App\Http\Controllers\Admin\LocationController;
use Illuminate\Support\Facades\Route;

//? API routes for admin functionalities
Route::prefix('api/admin')->group(function () {
    Route::get('/test', function () {
        dd("Admin route reached");
    });


    // LOCATION ROUTES
    Route::apiResource('locations', LocationController::class);
});