<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Manejo de excepciones de JWT
        if ($exception instanceof TokenExpiredException) {
            return response()->json(['error' => 'Token expirado'], 401);
        } elseif ($exception instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token inválido'], 401);
        } elseif ($exception instanceof JWTException) {
            return response()->json(['error' => 'Token ausente'], 401);
        }

        return parent::render($request, $exception);
    }
}