<?php

use App\Http\Middleware\VerifyAccessKey;
use App\Http\Middleware\XssSanitization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Nand\License\Http\Middleware\VerifyLicense;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(XssSanitization::class);
        $middleware->append(VerifyLicense::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
