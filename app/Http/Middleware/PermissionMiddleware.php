<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;
class PermissionMiddleware
{
    public function handle(Request $request, Closure $next,$action)
    {
        $moduleName = $request->segment(1);
        $module = Module::where('page_url', '/'.$moduleName)->where('active',1)->first();
        if ($moduleName === 'permissions' && Gate::allows('manage-permissions')) {
            return $next($request);
        }
        if (!$module) {
            abort(404, "Module '$moduleName' not found.");
        }
        if (!Gate::allows('has-permission', [$module->module_id,$action])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "You do not have permission to {$action}  {$moduleName}."
                ], 403);
            }
            return response()->view('errors.permission', [
                'action' => $action,
                'module' => $moduleName,
            ], 403);
        }
        return $next($request);
    }
}
