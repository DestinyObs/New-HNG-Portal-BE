<?php


//? API routes for talent functionalities

use App\Http\Controllers\Talent\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/talent')->group(function () {
    Route::get('/test', function () {
        dd("Talent route reached");
    });

    Route::middleware('auth:sanctum')->group(function () {

        //? TALENT PROFILE CONTROLLER AND ROUTES
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'show');
            Route::put('profile/change-password', 'changePassword');
            Route::put('profile/photo', 'updatePhoto');
        });
    });
});
