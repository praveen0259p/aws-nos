<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\ApplicationWindow;
use Carbon\Carbon;
class CheckApplicationWindow
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user()->id;
        if (! $user) {
            abort(401);
        }
        $window = ApplicationWindow::where('active',1)->latest()->first();
        if (! $window) {
            abort(403, 'No active application window');
        }
        $application = Application::where('user_id', $user)->where('window_id', $window->id)->latest()->first();
        if (! $application || $application->application_status === 0) {
            if ($window->isEditOpen()) {
                abort(403, 'Your application is not submitted; you cannot edit your application.');
            }
            if (! $window->isSubmissionOpen()) {
                abort(403, 'Submission window not open');
            }
            
            return $next($request);
        }
        if ($application->application_status === 1) {
            if (! $window->isEditOpen()) {
                abort(403, 'Edit window not open');
            }
            return $next($request);
        }
        abort(403, 'Action not allowed');
    }
}
