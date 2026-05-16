<?php

namespace App\Http\Controllers;

use App\Models\ApplicationWindow;
use App\Models\Minister;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Banner;
use App\Models\Document;
use App\Models\LoginHistory;
use App\Models\MenuItem;
use App\Models\Module;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\UploadAssetTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class BackendController extends Controller
{
    use UploadAssetTrait;
    public function dashboard()
    {
        return view('backend.dashboard');
    }
    public function banners()
    {
        $banners = Banner::with(['asset', 'user.role'])->get();
        return view('backend.cms.banners.list', compact('banners'));
    }
    public function create()
    {
        return view('backend.cms.banners.create');
    }
    public function createbanner(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'file' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'ordering' => 'required|unique:banners,priority_ordering',
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ], [
            'title.required' => 'Banner title is required',
            'title.regex' => 'Banner title can only contain letters and spaces',
            'file.max' => 'Please upload a file smaller or equal to 5MB.'
        ]);
        $banner = new Banner();
        $banner->title = $validated['title'];
        $banner->priority_ordering = $validated['ordering'];
        $banner->active = $validated['status'];
        $banner->created_by = Auth::id();
        $banner->assets_id = $this->uploadAssetToPublic($request->file('file'), 'images/banners');
        $banner->save();
        return redirect()->route('banners.list')->with('success', 'Banner created successfully');
    }
    public function editbanner($bannerId)
    {
        $banner = Banner::with(['asset'])->findOrFail(decrypt($bannerId));
        return view('backend.cms.banners.edit', compact('banner'));
    }
    public function updatebanner(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'ordering' => 'required|unique:banners,priority_ordering,' . $banner->id,
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ], [
            'title.required' => 'Banner title is required',
            'title.regex' => 'Banner title can only contain letters and spaces',
            'file.max' => 'Please upload a file smaller or equal to 5MB.'
        ]);

        $data = [
            'title'             => $request->title,
            'priority_ordering' => $request->ordering,
            'active'            => $request->status,
        ];
        if ($request->hasFile('file')) {
            $newAssetId = $this->updateAssetInPublic($request->file('file'), $banner->assets_id, 'images/banners');
            $data['assets_id'] = $newAssetId;
        }
        $banner->update($data);
        return redirect()->route('banners.list')->with('success', 'Banner updated successfully!');
    }
    public function bannerstatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'status' => 'required|boolean'
        ]);
        $banner = Banner::findOrFail($request->id);
        $banner->active = $request->status;
        $banner->save();
        if ($banner->active) {
            return response()->json([
                'success' => true,
                'status' => $banner->active,
                'message' => 'Banner activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $banner->active,
                'message' => 'Banner deactivated successfully.'
            ]);
        }
    }
    public function bannerdelete(Request $request)
    {
        try {
            $banner = Banner::findOrFail($request->id);
            if ($banner->assets_id) {
                $this->deleteAssetById($banner->assets_id);
            }
            $banner->delete();
            return response()->json([
                'success' => true,
                'message' => 'Banner and related assets deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Banner could not be deleted.'
            ], 400);
        }
    }
    public function ministers()
    {
        $ministers = Minister::with(['asset', 'user.role'])->get();
        return view('backend.cms.ministers.list', compact('ministers'));
    }
    public function createminister()
    {
        return view('backend.cms.ministers.create');
    }
    public function saveminister(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'file' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'ordering' => 'required|unique:ministers,priority_ordering',
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ], [
            'title.required' => 'Minister name is required',
            'title.regex' => 'Minister name can only contain letters and spaces',
            'file.max' => 'Please upload a file smaller or equal to 5MB.'
        ]);
        $minister = new Minister();
        $minister->name = $validated['title'];
        $minister->priority_ordering = $validated['ordering'];
        $minister->active = $validated['status'];
        $minister->created_by = Auth::id();
        $minister->assets_id = $this->uploadAssetToPublic($request->file('file'), 'images/ministers');
        $minister->save();
        return redirect()->route('ministers.list')->with('success', 'Minister created successfully');
    }
    public function editminister($ministerId)
    {
        $minister = Minister::with(['asset'])->findOrFail(decrypt($ministerId));
        return view('backend.cms.ministers.edit', compact('minister'));
    }
    public function updateminister(Request $request)
    {
        $minister = Minister::findOrFail($request->id);
        $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'ordering' => 'required|unique:ministers,priority_ordering,' . $minister->id,
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ], [
            'title.required' => 'Minister name is required',
            'title.regex' => 'Minister name can only contain letters and spaces',
            'file.max' => 'Please upload a file smaller or equal to 5MB.'
        ]);

        $data = [
            'name'             => $request->title,
            'priority_ordering' => $request->ordering,
            'active'            => $request->status,
        ];
        if ($request->hasFile('file')) {
            $newAssetId = $this->updateAssetInPublic($request->file('file'), $minister->assets_id, 'images/ministers');
        }
        $minister->update($data);
        return redirect()->route('ministers.list')->with('success', 'Minister updated successfully!');
    }
    public function ministerstatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ministers,id',
            'status' => 'required|boolean'
        ]);
        $minister = Minister::findOrFail($request->id);
        $minister->active = $request->status;
        $minister->save();
        if ($minister->active) {
            return response()->json([
                'success' => true,
                'status' => $minister->active,
                'message' => 'Minister activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $minister->active,
                'message' => 'Minister deactivated successfully.'
            ]);
        }
    }
    public function ministerdelete(Request $request)
    {
        try {
            $minister = Minister::findOrFail($request->id);
            if ($minister->assets_id) {
                $this->deleteAssetById($minister->assets_id);
            }
            $minister->delete();
            return response()->json([
                'success' => true,
                'message' => 'Minister and related assets deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Minister could not be deleted.'
            ], 400);
        }
    }
    public function documents()
    {
        $documents = Document::with(['asset', 'menuItem', 'user.role'])->get();
        //dd($documents);
        return view('backend.cms.documents.list', compact('documents'));
    }
    public function createDocument()
    {
        $type = MenuItem::where(['type' => 1, 'active' => 1])->pluck('title', 'id');
        return view('backend.cms.documents.create', compact('type'));
    }
    public function savedocument(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z0-9\s.\-_]+$/'],
            'menu' => 'required|exists:menu_items,id',
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
            'file' => 'required|mimes:pdf|max:10240',
        ], [
            'title.required' => 'Document title is required',
            'title.regex' => 'The title may only contain letters, numbers, spaces, dots, hyphens, and underscores.',
            'file.max' => 'Please upload a file smaller or equal to 10MB.'
        ]);
        $document = new Document();
        $document->title = $validated['title'];
        $document->parent_menu_id = $validated['menu'];
        $document->active = $validated['status'];
        $document->created_by = Auth::id();
        $document->assets_id = $this->uploadAssetToPublic($request->file('file'), 'documents');
        $document->save();
        return redirect()->route('documents.list')->with('success', 'Document created successfully');
    }
    public function editDocument($documentId)
    {
        $document = Document::with(['asset'])->findOrFail(decrypt($documentId));
        $type = MenuItem::where(['type' => 1, 'active' => 1])->pluck('title', 'id');
        return view('backend.cms.documents.edit', compact('document', 'type'));
    }
    public function updatedocument(Request $request)
    {
        $document = Document::findOrFail($request->id);
        $validated = $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z0-9\s.\-_]+$/'],
            'menu' => 'required|exists:menu_items,id',
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
            'file' => 'nullable|mimes:pdf|max:10240',
        ], [
            'title.required' => 'Document title is required',
            'title.regex' => 'The title may only contain letters, numbers, spaces, dots, hyphens, and underscores.',
            'file.max' => 'Please upload a file smaller or equal to 10MB.'
        ]);
        $data = [
            'title' => $validated['title'],
            'parent_menu_id' => $validated['menu'],
            'status' => $validated['status'],
            'active' => $validated['status'],
        ];
        if ($request->hasFile('file')) {
            $newAssetId = $this->updateAssetInPublic($request->file('file'), $document->assets_id, 'documents');
        }
        $document->update($data);
        return redirect()->route('documents.list')->with('success', 'Document updated successfully');
    }
    public function documentstatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'status' => 'required|boolean'
        ]);
        $document = Document::findOrFail($request->id);
        $document->active = $request->status;
        $document->save();
        if ($document->active) {
            return response()->json([
                'success' => true,
                'status' => $document->active,
                'message' => 'Document activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $document->active,
                'message' => 'Document deactivated successfully.'
            ]);
        }
    }
    public function documentsdelete(Request $request)
    {
        try {
            $document = Document::findOrFail($request->id);
            if ($document->assets_id) {
                $this->deleteAssetById($document->assets_id);
            }
            $document->delete();
            return response()->json([
                'success' => true,
                'message' => 'Document and related assets deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document could not be deleted.'
            ], 400);
        }
    }
    public function menus(Request $request)
    {
        $menu = MenuItem::whereNull('parent_id')
            ->with(['user.role', 'childrenRecursive.user.role'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('active', $request->status);
            })
            ->when($request->filled('page_type'), function ($q) use ($request) {
                $q->where('type', $request->page_type);
            })
            ->menuType((int) request('type'))
            ->orderBy('order_index')
            ->get();
        return view('backend.cms.menus.list', compact('menu'));
    }
    public function menustatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:menu_items,id',
            'status' => 'required|boolean'
        ]);
        $menu = MenuItem::with('childrenRecursive')->findOrFail($request->id);
        $hasActiveChildren = $menu->childrenRecursive->contains(fn($child) => $child->active);
        if ($hasActiveChildren) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change status. Please deactivate all child menus first.'
            ], 400);
        }
        $menu->active = $request->status;
        $menu->save();
        if ($menu->active) {
            return response()->json([
                'success' => true,
                'status' => $menu->active,
                'message' => 'Menu activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $menu->active,
                'message' => 'Menu deactivated successfully.'
            ]);
        }
    }
    public function menudelete(Request $request)
    {
        try {
            $menu = MenuItem::with('children')->findOrFail($request->id);
            if ($menu->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu cannot be deleted because it has child menus.'
                ], 400);
            }
            $menu->delete();
            return response()->json([
                'success' => true,
                'message' => 'Menu deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu could not be deleted.' . $e
            ], 400);
        }
    }
    public function createmenus()
    {
        $menu = MenuItem::pluck('title', 'id');
        return view('backend.cms.menus.create', compact('menu'));
    }
    public function savemenu(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'url' => 'required',
            'parent' => 'nullable|exists:menu_items,id',
            'target' => ['required', 'in:' . implode(',', array_keys(targettype()))],
            'page_type'   => ['required', 'in:' . implode(',', array_keys(pagetype()))],
            'menu_type' => ['required', 'in:' . implode(',', array_keys(MenuItem::menuTypeOptions()))],
            'ordering' => ['required', 'integer', 'unique:menu_items,order_index'],
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ]);
        MenuItem::create([
            'title'       => $validated['title'],
            'url'         => $validated['url'],
            'target'      => $validated['target'],
            'parent_id'   => $validated['parent'],
            'order_index' => $validated['ordering'],
            'type'        => $validated['page_type'],
            'active'      => $validated['status'],
            'is_main'     => in_array($validated['menu_type'], [1, 3]) ? 1 : 0,
            'is_footer'   => in_array($validated['menu_type'], [2, 3]) ? 1 : 0,
            'created_by'  => Auth::id(),
        ]);
        return redirect()->route('menus.list')->with('success', 'Menu Item created successfully!');
    }
    public function editmenu($id)
    {
        //dd(decrypt($id));
        $menuItem = MenuItem::pluck('title', 'id');
        $menu = MenuItem::findOrFail(decrypt($id));
        return view('backend.cms.menus.edit', compact('menu', 'menuItem'));
    }
    public function updatemenu(Request $request)
    {
        //dd($request->all());
        $menu = MenuItem::findOrFail($request->id);
        $validated = $request->validate([
            'title' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'url' => 'required',
            'parent' => 'nullable|exists:menu_items,id',
            'target' => ['required', 'in:' . implode(',', array_keys(targettype()))],
            'page_type'   => ['required', 'in:' . implode(',', array_keys(pagetype()))],
            'menu_type' => ['required', 'in:' . implode(',', array_keys(MenuItem::menuTypeOptions()))],
            'ordering' => 'required|integer|unique:menu_items,order_index,' . $request->id,
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ]);
        $menu->update([
            'title' => $validated['title'],
            'url' => $validated['url'],
            'parent' => $validated['parent'],
            'target' => $validated['target'],
            'type' => $validated['page_type'],
            'order_index' => $validated['ordering'],
            'status' => $validated['status'],
            'is_main' => in_array((int) $validated['menu_type'], [1, 3]),
            'is_footer' => in_array((int) $validated['menu_type'], [2, 3]),
        ]);
        return redirect()->route('menus.list')->with('success', 'Menu Item updated successfully');
    }
    public function childmenus(Request $request, $id)
    {
        $menu = MenuItem::where('parent_id', decrypt($id))
            ->with(['user.role', 'childrenRecursive.user.role'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('active', $request->status);
            })
            ->when($request->filled('page_type'), function ($q) use ($request) {
                $q->where('type', $request->page_type);
            })
            ->menuType((int) request('type'))
            ->orderBy('order_index')
            ->get();
        return view('backend.cms.menus.child', compact('menu', 'id'));
    }
    public function modules()
    {
        $modules = Module::with(['user.role', 'children'])->whereNull('parent_id')->orderBy('id')->get();
        return view('backend.cms.modules.list', compact('modules'));
    }
    public function createmodules()
    {
        $module = Module::where('active', 1)->pluck('module_name', 'module_id');
        //dd($module);
        return view('backend.cms.modules.create', compact('module'));
    }
    public function childmodules(Request $request, $id)
    {
        //dd(decrypt($id));
        $modules = Module::where('parent_id', decrypt($id))
            ->with(['user.role', 'children'])
            // ->when($request->filled('status'), function ($q) use ($request) {
            //     $q->where('active', $request->status);
            // })
            // ->when($request->filled('page_type'), function ($q) use ($request) {
            //     $q->where('type', $request->page_type);
            // })
            ->orderBy('position')
            ->get();
        return view('backend.cms.modules.child', compact('modules', 'id'));
    }
    public function savemodules(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'moduleid' => ['required', 'integer', 'unique:modules,module_id'],
            'parent' => 'nullable|exists:modules,module_id',
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'url' => 'required',
            'position' => ['required', 'integer'],
            'icon' => ['required'],
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ]);
        Module::create([
            'module_id'  => $validated['moduleid'],
            'parent_id'  => $validated['parent'],
            'module_name'       => $validated['name'],
            'page_url'        => $validated['url'],
            'position'   => $validated['position'],
            'icon_name'       => $validated['icon'],
            'active'     => $validated['status'],
            'created_by' => Auth::id(),
        ]);
        return redirect()->route('modules.list')->with('success', 'Module created successfully!');
    }
    public function modulesstatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modules,id',
            'status' => 'required|boolean'
        ]);
        $module = Module::with('children')->findOrFail($request->id);
        $hasActiveChildren = $module->children->contains(fn($child) => $child->active);
        if ($hasActiveChildren) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change status. Please deactivate all child modules first.'
            ], 400);
        }
        $module->active = $request->status;
        $module->save();
        if ($module->active) {
            return response()->json([
                'success' => true,
                'status' => $module->active,
                'message' => 'Module activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $module->active,
                'message' => 'Module deactivated successfully.'
            ]);
        }
    }
    public function editmodules($id)
    {
        $module = Module::findOrFail(decrypt($id));
        $modules = Module::where('active', 1)->pluck('module_name', 'module_id');
        return view('backend.cms.modules.edit', compact('modules', 'module', 'id'));
    }
    public function updatemodule(Request $request)
    {
        $validated = $request->validate([
            'id'  => ['required', 'exists:modules,id'],
            'moduleid' => 'required|integer|unique:modules,module_id,' . $request->id,
            'parent' => 'nullable|exists:modules,module_id',
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'url' => 'required',
            'position' => ['required', 'integer'],
            'icon' => ['required'],
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ]);
        $module = Module::findOrFail($validated['id']);
        $module->update([
            'module_id'   => $validated['moduleid'],
            'parent_id'   => $validated['parent'],
            'module_name' => $validated['name'],
            'page_url'    => $validated['url'],
            'position'    => $validated['position'],
            'icon_name'   => $validated['icon'],
            'active'      => $validated['status'],
            'updated_by'  => Auth::id(),
        ]);
        return redirect()->route('modules.list')->with('success', 'Module updated successfully!');
    }
    public function modulesdelete(Request $request)
    {
        try {
            $menu = Module::with('children')->findOrFail($request->id);
            if ($menu->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module cannot be deleted because it has child modules.'
                ], 400);
            }
            $menu->delete();
            return response()->json([
                'success' => true,
                'message' => 'Module deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Module could not be deleted.' . $e
            ], 400);
        }
    }
    public function users()
    {
        $roleWiseUsers = Role::withCount('users')->get();
        return view('backend.cms.users.list', compact('roleWiseUsers'));
    }
    public function usersByRole($roleId)
    {
        $role = Role::findOrFail(decrypt($roleId));
        $users = $role->users;
        return view('backend.cms.users.roletype', compact('users', 'role'));
    }
    public function userstatus(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|boolean'
        ]);
        $user = User::findOrFail($request->id);
        $user->active = $request->status;
        $user->save();
        if ($user->active) {
            return response()->json([
                'success' => true,
                'status' => $user->active,
                'message' => 'User activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $user->active,
                'message' => 'User deactivated successfully.'
            ]);
        }
    }
    public function createuser()
    {
        $roles = Role::whereNotIn('role_id', [1, 2])->pluck('name', 'role_id');
        //dd($roles);
        return view('backend.cms.users.create', compact('roles'));
    }
    public function saveuser(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'middlename' => ['nullable', 'string', 'regex:/^[a-zA-Z]+$/'],
            'lastname'  => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'mobile'    => 'required|numeric|digits:10',
            'gender'   => 'required|in:' . implode(',', array_keys(genderOptions())),
            'role' => 'required|exists:roles,role_id',
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ], [
            'firstname.required' => 'Enter First Name',
            'firstname.regex'    => 'First Name must contain letters only',
            'middlename.regex'   => 'Middle Name must contain letters only',
            'lastname.required'  => 'Enter Last Name',
            'lastname.regex'     => 'Last Name must contain letters only',
            'email.required'     => 'Enter Email',
            'email.email'        => 'Enter a valid Email',
            'mobile.required'    => 'Enter Mobile Number',
            'mobile.digits_between' => 'Mobile number must be numeric and valid length',
            'gender.required'    => 'Please select Gender',
            'gender.in'          => 'Invalid Gender selected',
            'role.required'    => 'Please choose Role',
            'role.exist'          => 'Invalid Role selected',
            'status.required'    => 'Please choose Status',
            'status.in'          => 'Invalid Status selected',
        ]);
        $user = User::create([
            'role_id' => $validatedData['role'],
            'firstname' => $validatedData['firstname'],
            'middlename' => $validatedData['middlename'] ?? null,
            'lastname' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'gender' => $validatedData['gender'],
            'active' => $validatedData['status'],
            'password' => hash('sha256', 'NOS#@!2025'),
        ]);
        return redirect()->route('users.list')->with('success', 'User Created successfully!');
    }
    public function role()
    {
        $roles = Role::all();
        return view('backend.cms.role.list', compact('roles'));
    }
    public function createrole()
    {
        return view('backend.cms.role.create');
    }
    public function saverole(Request $request)
    {
        $validatedData = $request->validate([
            'roleid' => ['required', 'integer', 'unique:roles,role_id'],
            'role' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'status' => ['required', 'in:' . implode(',', array_keys(statusoptions()))],
        ], [
            'role.required' => 'Enter Role Name',
            'role.regex'    => 'Role must contain letters only',
            'status.required'    => 'Please choose Status',
            'status.in'          => 'Invalid Status selected',
        ]);
        Role::create([
            'role_id' => $validatedData['roleid'],
            'name' => $validatedData['role'],
            'status' => $validatedData['status']
        ]);
        return redirect()->route('role.list')->with('success', 'Role Created successfully!');
    }
    public function rolestatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:roles,role_id',
            'status' => 'required|boolean'
        ]);
        $role = Role::findOrFail($request->id);
        $role->active = $request->status;
        $role->save();
        if ($role->active) {
            return response()->json([
                'success' => true,
                'status' => $role->active,
                'message' => 'Role activated successfully.'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'status' => $role->active,
                'message' => 'Role deactivated successfully.'
            ]);
        }
    }
    public function permissionsList()
    {
        //dd('sdsf');
        $permissions = Permission::with(['role', 'module'])->get();
        return view('backend.permission.list', compact('permissions'));
    }
    public function permissionstatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'         => 'required|integer|exists:permissions,id',
                'permission' => 'required|string|in:can_view,can_create,can_edit,can_delete',
                'status'     => 'required|boolean'
            ]);

            $permission = Permission::findOrFail($validated['id']);
            $status= $validated['status'] == 1 ? 0 : 1;
            $permission->{$validated['permission']} = $status;
            $permission->save();
            return response()->json([
                'success' => true,
                'status'  => (int)$status,
                'message' => 'Permission updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Permission update failed', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
    public function createpermission()
    {
        $modules = Module::pluck('module_name', 'module_id');
        $roles=Role::pluck('name', 'role_id');
        return view('backend.permission.create',compact('modules','roles'));
    }
    public function savepermissions(Request $request)
    {
        $request->validate([
            'role'         => 'required|integer|exists:roles,role_id',
            'modules'         => 'required|integer|exists:modules,module_id',
        ]);
        $exists = Permission::where('role_id', $request->role)->where('module_id', $request->modules)->first();
        if ($exists) {
            return redirect()->route('permissions.list')->with('error', 'Permission already exists for this role and module.');
        }
        Permission::create([
            'role_id'       => $request->input('role'),
            'module_id'     => $request->input('modules'),
            'can_view'   => $request->has('can_view') ? 1 : 0,
            'can_create' => $request->has('can_create') ? 1 : 0,
            'can_edit'   => $request->has('can_edit') ? 1 : 0,
            'can_delete' => $request->has('can_delete') ? 1 : 0,
        ]);
        return redirect()->route('permissions.list')->with('success', 'Permission added successfully!');
    }
    public function logs()
    {
        $histories=LoginHistory::with('user','user.role')->orderBy('created_at', 'desc')->get();
        return view('backend.logs.list',compact('histories'));
    }
}
