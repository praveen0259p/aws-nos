<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }
    public function boot(): void
    {
        // $filePath = public_path('countries.csv');
        // if (file_exists($filePath)) {
        //     echo "File exists!";
        // } else {
        //     echo  "File does NOT exist!";
        // }
        // $data = Excel::toArray([], $filePath);
        // for ($i = 1; $i <= count($data[0])-1; $i++) {
        //     echo"<pre>";
        //     print_r($data[0][$i][0]);
        //     print_r($data[0][$i][1]);
        //     echo "<br>";
        //     DB::table('countries')->insert([
        //         'name'        => $data[0][$i][0],
        //         'code'        => $data[0][$i][1],
        //         'is_active'   => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
        
    
        Gate::define('has-permission', function ($user, $module_id, $action) {
            $permission = Permission::where(['role_id'=>$user->role->id,'module_id'=>$module_id])->first();
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
        Gate::define('manage-permissions', function ($user) {
            return $user->role->id === 1;
        });
    }
}
