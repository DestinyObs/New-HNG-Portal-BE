<?php

use Illuminate\Foundation\Application;
use App\Exceptions\Handler as AppHandler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Configuration\Middleware;

$appBuilder = Application::configure(basePath: dirname(__DIR__));

$app = $appBuilder
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        
    })
    ->create();

$app->singleton(ExceptionHandler::class, AppHandler::class);

return $app;
