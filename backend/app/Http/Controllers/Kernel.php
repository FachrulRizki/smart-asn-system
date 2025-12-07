<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

// Global
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;

// Web middleware
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Routing
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;

// Auth
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

// Signed route
use Illuminate\Routing\Middleware\ValidateSignature;

class Kernel extends HttpKernel
{
    protected $middleware = [
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Http\Middleware\HandleCors::class,
            ThrottleRequests::class . ':api',
            SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'verified' => EnsureEmailIsVerified::class,
        'signed' => ValidateSignature::class,
        'throttle' => ThrottleRequests::class,
    ];
}
