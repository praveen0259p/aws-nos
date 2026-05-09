<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::getToken()?->get();
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user || $token !== $user->token) {
                return response()->json(['error' => 'Unauthorized or token mismatch'], 401);
            }
            return $next($request);

        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}

