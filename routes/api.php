<?php

use App\Http\Controllers\WaitlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\FaqController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/waitlist', [WaitlistController::class, 'store']);
Route::get('/waitlist/{waitlist}', [WaitlistController::class, 'show']);

//Jobs API
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/search', [JobController::class, 'search']);
Route::get('/jobs/{job}', [JobController::class, 'show']);
Route::get('/jobs/{job}/related', [JobController::class, 'related']);

//FAQs
Route::get('/faq', [FaqController::class, 'index']);
Route::get('/faq/{faq}', [FaqController::class, 'show']);
