<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([]);
        $middleware->api([]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth:api' => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })
    ->create();

/**
 * Obtiene el código de estado HTTP para la excepción.
 *
 * @param  \Exception  $exception
 * @return int
 */
function getStatusCode(Exception $exception)
{
    if (method_exists($exception, 'getStatusCode')) {
        return $exception->getStatusCode();
    }

    return 500; 
}