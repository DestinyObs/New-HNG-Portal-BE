<?php

use Illuminate\Support\Facades\Route;

//? API routes for admin functionalities
Route::prefix('api/admin')->group(function () {
    Route::get('/test', function () {
        dd("Admin route reached");
    });

    // Admin user management routes
    Route::get('/users', [App\Http\Controllers\Admin\AdminUserController::class, 'index']);
    Route::get('/users/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'show']);
    Route::patch('/users/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'update']);
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\AdminUserController::class, 'destroy']);
    Route::post('/users/{id}/restore', [App\Http\Controllers\Admin\AdminUserController::class, 'restore']);
    // Route::post('/users/{id}/impersonate', [App\Http\Controllers\Admin\AdminUserController::class, 'impersonate']);
});
