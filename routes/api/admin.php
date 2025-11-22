<?php

use App\Http\Controllers\SkillController;
use App\Http\Controllers\Admin\JobTypeController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

//? API routes for admin functionalities
Route::prefix('api/admin')->group(function () {
    Route::get('/test', function () {
        dd("Admin route reached");
    });

    // SKILLS ROUTES
    Route::apiResource('skills', SkillController::class)->names([
        'index' => 'admin.skills.index',
        'store' => 'admin.skills.store',
        'show' => 'admin.skills.show',
        'update' => 'admin.skills.update',
        'destroy' => 'admin.skills.destroy',
    ]);

    // JOB TYPES ROUTES
    Route::apiResource('job-types', JobTypeController::class)->names([
        'index' => 'admin.job-types.index',
        'store' => 'admin.job-types.store',
        'show' => 'admin.job-types.show',
        'update' => 'admin.job-types.update',
        'destroy' => 'admin.job-types.destroy',
    ]);

    // TAGS ROUTES
    Route::apiResource('tags', TagController::class);

});
