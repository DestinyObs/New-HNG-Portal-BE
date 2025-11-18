<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
  Route::post('/register', action: [UserController::class, 'store']);
  
Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);
    Route::post('forgot-password', [ForgotPasswordController::class, 'store']);
    Route::post('reset-password', [ForgotPasswordController::class, 'update']);
});

Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/waitlist/{waitlist}', [WaitlistController::class, 'show']);

