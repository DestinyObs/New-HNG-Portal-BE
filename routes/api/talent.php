<?php


//? API routes for talent functionalities

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('talent')->middleware('auth:sanctum')->group(function () {
    Route::get('/test', function () {
        dd("Talent route reached");
    });

    Route::put('update-password', [UserController::class, 'updatePassword']);
    Route::put('update-photo', [UserController::class, 'updatePhoto']);
});
