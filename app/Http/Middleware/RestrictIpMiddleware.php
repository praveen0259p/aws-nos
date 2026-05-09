<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = ['10.22.13.216','10.22.13.217','10.22.13.202','10.22.13.231','10.22.13.204','164.100.77.86'];

        if (!in_array($request->ip(), $allowedIps)) {
            abort(403, 'Access denied: Unauthorized IP address.');
        }

        return $next($request);
    }
}
