<?php

use Illuminate\Support\Facades\Route;

//? API routes for admin functionalities
Route::prefix('api/admin')->group(function () {
    Route::get('/test', function () {
        dd("Admin route reached");
    });
});