<?php

use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

//? API routes for admin functionalities
Route::prefix('api/admin')->group(function () {
    Route::get('/test', function () {
        dd("Admin route reached");
    });

    // SKILLS ROUTES
    Route::apiResource('skills', SkillController::class);
});