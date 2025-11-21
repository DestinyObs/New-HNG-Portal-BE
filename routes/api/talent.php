<?php


//? API routes for talent functionalities

use Illuminate\Support\Facades\Route;

Route::prefix('api/talent')->group(function () {
    Route::get('/test', function () {
        dd("Talent route reached");
    });
});