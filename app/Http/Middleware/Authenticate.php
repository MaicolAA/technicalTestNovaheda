<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Closure;


class Authenticate 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $isValid = Auth::guard($guard)->check();
        if (!$isValid) {
            return response()->json([
                "status" => 401,
                "message" => __('messages.error_token'),
                'error' => [__('messages.error_token')]
            ], 401);

        }

        $user = Auth::guard($guard)->user();
        Log::info('Usuario autenticado:', ['user' => $user ]);

        return $next($request);
    }
}