<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class ExternalApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $tokenType = $payload->get('token_type');

            if ($tokenType !== 'external_api') {
                return response()->json(['error' => 'Unauthorized: Invalid API token'], 401);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid or missing token'], 401);
        }

        return $next($request);
    }
}
