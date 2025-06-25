<?php

use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\MinifyHtml;
use App\Http\Middleware\RestrictLoggedIn;
use App\Http\Middleware\VerifyAccessKey;
use App\Http\Middleware\XssSanitization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Nk\SystemAuth\Http\Middleware\EnsureKeyVerified;
use Nk\SystemAuth\Http\Middleware\EnsurePackagePresent;
use \Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(StartSession::class);
        $middleware->append(ShareErrorsFromSession::class);
        $middleware->alias([
            'restrict.login' => RestrictLoggedIn::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
