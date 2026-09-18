<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottle;

class AdminLoginThrottle extends BaseThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  int|string  $decayMinutes
     * @param  string  $prefix
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        // Apply rate limiting only to admin login POST routes
        if ($request->is('admin/login') && $request->method() === 'POST') {
            return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
        }

        return $next($request);
    }
}