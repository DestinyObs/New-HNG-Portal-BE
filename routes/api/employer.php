<?php

//? API routes for employer functionalities

use Illuminate\Support\Facades\Route;

Route::prefix('api/employer')->group(function () {
    Route::get('/test', function () {
        dd("Employer route reached");
    });
});
