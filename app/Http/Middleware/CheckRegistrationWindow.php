<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRegistrationWindow
{
    public function handle(Request $request, Closure $next)
    {
        $window = getActiveRegistrationButton();
        if (! $window || ! $window->isSubmissionOpen()) {
            abort(403, 'Registration is currently closed');
        }
        return $next($request);
    }
}
