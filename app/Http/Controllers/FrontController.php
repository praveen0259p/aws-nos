<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Minister;
use App\Models\User;
use App\Models\Document;
use App\Models\MenuItem;
use App\Models\LoginHistory;
use App\Models\Content;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Route;
class FrontController extends Controller
{
    public function index()
    {
        $activeBanners = Banner::with('asset')->where('active', 1)->orderBy('priority_ordering', 'asc')->get();
        $activeMinisters = Minister::with('asset')->where('active', 1)->orderBy('priority_ordering', 'asc')->get();
        //dd($activeMinisters);
        return view('index', compact('activeBanners', 'activeMinisters'));
    }

    public function showRegistrationForm()
    {
        return view('register');
    }
    public function getDistricts($stateId)
    {
        return response()->json(getDistrictsByStateId($stateId));
    }
    public function register(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'firstname'  => 'required|alpha|max:50',
            'middlename' => 'nullable|alpha|max:50',
            'lastname'   => 'required|alpha|max:50',
            'father_name' => ['required', 'regex:/^[A-Za-z ]+$/', 'max:50'],
            'gender'   => 'required|in:' . implode(',', array_keys(genderOptions())),
            'dob' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'mobile'     => 'required|numeric|digits:10',
            'email'      => 'required|email|unique:users,email',
            'category'   => 'required|in:' . implode(',', array_keys(categoryOptions())),
            'state'      => 'required|exists:states,StateCode',
            'district'   => 'required|exists:districts,DistrictCode',
            'password'   => 'required|string',
            //'captcha'    => 'required',
        ], [
            'firstname.alpha' => 'Firstname can only contain letters.',
            'middlename.alpha' => 'Middlename can only contain letters.',
            'lastname.alpha' => 'Lastname can only contain letters.',
            'father_name.regex' => 'Father name can only contain letters and spaces.',
            'dob.before' => 'You must be at least 18 years old.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
        ]);
        $validatedData['role_id'] = 2;
        $user = User::create($validatedData);
        User::where('id', $user->id)->update(['regno' => generateRegistrationNumber($user->id)]);
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
        $user = User::where(['email' => $request->email, 'active' => 1])
        ->whereHas('role', function($query) {
            $query->where('active', 1); 
        })->first();
        // echo $request->password;
        // dd($user);
        $agent = new Agent();
        if ($user && $request->password == $user->password) {
            Auth::login($user);
            LoginHistory::create([
                'user_id'    => Auth::id(),
                'regno'      => Auth::user()->regno ?? null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'device'     => $agent->device(),
                'browser'    => $agent->browser(),
                'platform'   => $agent->platform(),
                'is_success' => true,
                'logged_in_at' => now(),
            ]);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
        }
        return back()->withErrors(['email' => 'Invalid Credentials',])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        LoginHistory::where('user_id', Auth::id())
        ->whereNull('logged_out_at')
        ->latest()
        ->update([
            'logged_out_at' => now()
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
    public function document()
    {
        return view('document');
    }
    public function yajraData(Request $request)
    {
        $menu=MenuItem::where(['url'=>$request->route])->first();
        $query = Document::with('asset')->where(['parent_menu_id' => $menu->id, 'active' => 1]);
        if (!$menu) return response()->json(['data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0]);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('title', fn($doc) => $doc->title)
            ->addColumn('published_date', fn($doc) => $doc->created_at->format('d-m-Y'))
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
    public function content(){
        
        $menu=Content::where(['slug'=>Route::currentRouteName()])->first();
        return view('content',compact('menu'));
    }
    public function sitemap()
    {
        return view('sitemap');
    }
}
