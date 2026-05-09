<?php

namespace App\Http\Controllers;

use App\Models\ApplicationWindow;
use App\Models\Module;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\UserModuleAccess;
use App\Models\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BackendController extends Controller
{

    public function dashboard()
    {
        $userAccessesmodules = UserModuleAccess::with('module')->where(['user_id' => Auth::user()->id, 'can_view' => 1])->get();
        foreach ($userAccessesmodules as $access) {
            echo $access->module->module_name;
        }
        //dd($userAccessesmodules);

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
        $window = getActiveRegistrationButton();
        if (!$window) {
            abort(403, 'Application Submission is Closed');
        }
        $application = Application::where(['user_id' => Auth::id(), 'window_id' => $window->id])->latest()->first();
        //dd($application);
        $maxSteps = 3;
        $activeStep = 1;
        // if ($window->isSubmissionOpen()) {
        //     if ($application && isset($application->steps)) {
        //         $activeStep = min($application->steps + 1, $maxSteps);
        //     }
        // }
        if ($application) {
            if ($window->isSubmissionOpen()) {
                $activeStep = min($application->steps + 1, $maxSteps);
            }
        } else {
            if ($window->isEditOpen()) {
                abort(403, 'You have not submitted any application, so you cannot edit.');
            }
        }
        return view('backend.application', compact('application', 'activeStep'));
    }
    public function step1(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $window = getActiveRegistrationButton();
        $userId = Auth::id();
        $windowId = $window->id;
        if ($window->isSubmissionOpen()) {
            $application = Application::firstOrNew([
                'user_id' => $userId,
                'window_id' => $windowId,
            ]);
            if (! $application->exists) {
                $application->application_number = generateApplicationNo();
            }
            $application->name = $request->name;
            $application->email = $request->email;
            $application->application_status = 0;
            $application->steps = 1;
            $application->save();

            return response()->json([
                'success' => true,
                'message' => "Step 1 saved Successfully"
            ]);
        }
        if ($window->isEditOpen()) {
            $application = Application::where([
                'user_id'   => $userId,
                'window_id' => $windowId,
            ])->firstOrFail();

            $old = [];
            $fields = ['name', 'email'];
            foreach ($fields as $field) {
                if ($request->has($field) && $application->$field != $request->$field) {
                    $old[$field] = $application->$field;
                    $application->$field = $request->$field;
                }
            }
            if (!empty($old)) {
                ApplicationHistory::create([
                    'application_id'     => $application->id,
                    'user_id'            => $userId,
                    'window_id'          => $windowId,
                    'application_number' => $application->application_number,
                    'name'               => $old['name'] ?? null,
                    'email'              => $old['email'] ?? null,
                    'steps'              => $application->steps,
                    'application_status' => $application->application_status,
                ]);
                $application->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Changes saved successfully'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'No changes detected'
            ]);
        }
    }
    public function stepe(Request $request)
    {
        $request->validate([
            'gender' => 'required',
            'dob' => 'required|email',
        ]);
        Application::updateOrCreate(
            ['reg_no' => Auth::user()->reg_no],
            $request->only('gender', 'dob')
        );

        return response()->json([
            'success' => true,
            'data' => $request->only('name', 'email')
        ]);
    }
    public function createForms(Request $request)
    {
        return view('backend.forms');
    }
    public function show(Form $form)
    {
        $formJson = json_decode($form->form_json); 
        return view('backend.show', compact('form','formJson'));
    }
    public function save(Request $request){
        dd($request->all());
    }
}
