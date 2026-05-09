<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\UserModuleAccess;
class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }
    public function boot(): void
    {
        // Define the gate
        Gate::define('has-permission', function ($user, $module_id, $action) {
            $permission = UserModuleAccess::where('user_id', $user->id)
                ->where('module_id', $module_id)
                ->first();
            if (!$permission) {
                return false;
            }
            return match ($action) {
                'view' => $permission->can_view,
                'create' => $permission->can_create,
                'edit' => $permission->can_edit,
                'delete' => $permission->can_delete,
                default => false,
            };
        });
    }
}
