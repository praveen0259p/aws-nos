<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Minister;
use App\Models\User;
use App\Models\Document;
use App\Models\MenuItem;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class FrontController extends Controller
{
    public function index()
    {
        // $menu=MenuItem::whereNull('parent_id')
        // ->where('active', 1)
        // ->orderBy('order_index')
        // ->with(['asset', 'childrenRecursive'])
        // ->get();
        // dd($menu);
        $activeBanners = Banner::with('asset')->where('active', 1)->orderBy('priority_ordering', 'asc')->get();
        $activeMinisters = Minister::with('asset')->where('active', 1)->orderBy('priority_ordering', 'asc')->get();
        return view('index', compact('activeBanners', 'activeMinisters'));
    }

    public function showRegistrationForm($year, $round)
    {
        return view('register', compact('year', 'round'));
    }
    public function getDistricts($stateId)
    {
        return response()->json(getDistrictsByStateId($stateId));
    }
    public function register(Request $request)
    {
        //dd($request->all());
        $activePortal = getActiveRegistrationButton();
        if (!$activePortal || $activePortal->year != $request->year || $activePortal->round != $request->round) {
            return redirect()->back()->with('error', 'Registration is not open for the selected year or round.');
        }
        $validatedData = $request->validate([
            'firstname'  => 'required|alpha|max:50',
            'middlename' => 'nullable|alpha|max:50',
            'lastname'   => 'required|alpha|max:50',
            'father_name'   => 'required|alpha|max:50',
            'gender'   => 'required|in:' . implode(',', array_keys(genderOptions())),
            'dob'        => 'required|date|before:today',
            'mobile'     => 'required|numeric|digits:10',
            'email'      => 'required|email|unique:users,email',
            'category'   => 'required|in:' . implode(',', array_keys(categoryOptions())),
            'state'      => 'required|exists:states,StateCode',
            'district'   => 'required|exists:districts,DistrictCode',
            'password'   => 'required|string',
            //'captcha'    => 'required',
        ]);
        $validatedData['role_id'] = 1;
        $validatedData['year'] = $request->year;
        $validatedData['round'] = $request->round;
        $user = User::create($validatedData);
        $insertedUserId = $user->id;
        $regnumber = str_pad($insertedUserId, 4, '0', STR_PAD_LEFT);
        $currentYear = date('Y');
        $nextYearLast2 = date('y', strtotime('+1 year'));
        $datePart = $currentYear . $nextYearLast2;
        $newregno = 'NOS' . $regnumber . $datePart;
        User::where('id', $insertedUserId)->update(['regno' => $newregno]);
        $user->notify(new WelcomeUser());
        return redirect()->route('login')->with('success', 'Registration successful!Please Activate Your Link');
    }
    public function activate($token)
    {
        $user = User::where('email', $token)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid activation link.');
        }
        $user->update(['active' => 1,]);
        return redirect()->route('login')->with('success', 'Account activated successfully! You can now login.');
    }
    public function showLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where(['email' => $request->email, 'active' => 1])->first();
        // echo $request->password;
        // dd($user);
        if ($user && $request->password == $user->password) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
        }
        return back()->withErrors(['email' => 'Invalid Credentials',])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
    public function schemeGuideline(Request $request)
    {
        // $documents=Document::where(['parent_menu_id'=>2,'active'=>1])->with('asset')->get();
        // return view('guidelines',compact('documents'));
        return view('guidelines');
    }
    public function yajraData(Request $request)
    {
        $menu=MenuItem::where(['url'=>$request->route])->first();
        $query = Document::with('asset')->where(['parent_menu_id' => $menu->id, 'active' => 1]);
        if (!$menu) return response()->json(['data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0]);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('title', fn($doc) => $doc->title)
            ->addColumn('published_date', fn($doc) => $doc->created_at->format('d.m.Y'))
            ->addColumn('size', fn($doc) => optional($doc->asset)->size_mb . ' MB')
            ->addColumn('actions', fn($doc) => '<a href="' . asset($doc->asset?->url) . '" target="_blank" class="view-btn"><i class="bi bi-eye me-1" aria-hidden="true"></i> View</a>')
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search')) {
                    $query->where('title', 'like', "%{$search}%");
                }
                if ($sortby = $request->input('sortby')) {
                    $query->orderBy('id', $sortby);
                }
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
    public function results()
    {
        // $results = Document::where(['parent_menu_id' => 20, 'active' => 1])->with('asset')->get();
        // return view('results', compact('results'));
        return view('guidelines');
    }
    public function forms()
    {
        // $forms = Document::where(['parent_menu_id' => 19, 'active' => 1])->with('asset')->get();
        // return view('forms', compact('forms'));
        return view('guidelines');
    }
}
