<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Module;
use App\Models\UserModuleAccess;
use Illuminate\Support\Facades\Auth;
class BackendController extends Controller
{
    
    public function dashboard()
    {
        $userAccessesmodules= UserModuleAccess::with('module')->where(['user_id'=>Auth::user()->id,'can_view'=>1])->get();
        foreach ($userAccessesmodules as $access) {
            echo $access->module->module_name;
        }
        dd($userAccessesmodules);

        // if (Gate::allows('has-permission', [$user, $module_id, 'view'])) {
        //     // User has permission to view
        // } else {
        //     // User does not have permission to view
        //     abort(403, 'Unauthorized action.');
        // }
        return view('backend.dashboard');
    }
    public function application()
    {
        return view('backend.application');
    }
}
