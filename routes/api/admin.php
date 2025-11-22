<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyVerificationController;
use App\Http\Controllers\Admin\TrackController;
use Illuminate\Support\Facades\Route;




//? API routes for admin functionalities
Route::prefix('api/admin')->group(function () {
    Route::get('/test', function () {
        dd("Admin route reached");
    });
});


Route::prefix('admin')->group(function () {

    /**
     * CATEGORY ROUTES
     */
    Route::controller(CategoryController::class)
        ->prefix('categories')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

    /**
     * TRACK ROUTES
     */
    Route::controller(TrackController::class)
        ->prefix('tracks')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

    /**
     * COMPANY VERIFICATION ROUTES
     */
    Route::controller(CompanyVerificationController::class)
        ->prefix('companies')
        ->group(function () {
            Route::get('/pending', 'pending');
            Route::put('/{uuid}/verify', 'verify');
        });

});