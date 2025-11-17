<?php

use App\Http\Controllers\WaitlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    dd("Reached her");
});
Route::post('/waitlist', [WaitlistController::class, 'store']);