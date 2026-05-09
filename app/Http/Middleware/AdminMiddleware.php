<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Please login to access this page.');
        //return redirect()->route('login')->with('error', 'You do not have admin access.');
    }
}
