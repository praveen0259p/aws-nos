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
        abort_if(! Auth::check(), 401, 'Unauthorized access');
        return $next($request);
        //return redirect()->route('login')->with('error', 'You do not have admin access.');
    }
}
