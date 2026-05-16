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
        $now = Carbon::now();
        if (! $user) {
            abort(401);
        }
        //$window = ApplicationWindow::where('active',1)->latest()->first();
        $window = ApplicationWindow::where(function ($query) use ($now) {
            $query->where(function ($q) use ($now) {
                $q->whereDate('application_open_date', '<=', $now)
                ->whereDate('application_close_date', '>=', $now);
            })
            ->orWhere(function ($q) use ($now) {
                $q->whereDate('edit_start_date', '<=', $now)
                ->whereDate('edit_end_date', '>=', $now);
            });
        })
        ->latest()->first();
        //dd($window);
        if (! $window) {
            abort(403, 'No active application window');
        }

        $request->attributes->add(['active_window' => $window]);
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
