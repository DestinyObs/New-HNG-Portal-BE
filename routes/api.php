<?php

use App\Http\Controllers\Admin\JobTypeController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/waitlist/{waitlist}', [WaitlistController::class, 'show']);



// JOB TYPES ROUTES
Route::apiResource('job-types', JobTypeController::class)
    ->only(['index', 'show']);