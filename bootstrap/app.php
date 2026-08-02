<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
            'student' => \App\Http\Middleware\StudentMiddleware::class,
            'localization' => \App\Http\Middleware\Localization::class,  // Menambahkan middleware localization
            'web' => \App\Http\Middleware\LogUserActivity::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'payment/toyyibpay-callback',
            'webauthn/login/options',
            'webauthn/login',
            'webauthn/register/options',
            'webauthn/register',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
