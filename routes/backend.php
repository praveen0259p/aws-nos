<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;

Route::get('/dashboard', [BackendController::class, 'dashboard'])->name('dashboard')->middleware('checkPermission:view');
Route::prefix('application-form')->middleware(['applicationWindow'])->group(function () {
    Route::get('/', [ApplicationController::class, 'applicationForm'])->name('application-form.create');
    Route::post('/savepersonal', [ApplicationController::class, 'savepersonal'])->name('personal.save');
    Route::post('/saveforeign', [ApplicationController::class, 'saveforeign'])->name('foreign.save');
    Route::post('/saveemployment', [ApplicationController::class, 'saveemployment'])->name('employment.save');
    Route::post('/savevisa', [ApplicationController::class, 'savevisa'])->name('visa.save'); 
});
// Start Banner Route
Route::prefix('banners')->group(function () {
    Route::get('/', [BackendController::class, 'banners'])->name('banners.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'create'])->name('banners.create')->middleware('checkPermission:create');
    Route::post('/createbanner', [BackendController::class, 'createbanner'])->name('banners.createbanner')->middleware('checkPermission:create');
    Route::get('/{id}', [BackendController::class, 'editbanner'])->name('banners.edit')->middleware('checkPermission:edit');
    Route::post('/updatebanner', [BackendController::class, 'updatebanner'])->name('banners.updatebanner')->middleware('checkPermission:edit');
    Route::post('/bannerstatus', [BackendController::class, 'bannerstatus'])->name('banners.status')->middleware('checkPermission:edit');
    Route::post('/delete', [BackendController::class, 'bannerdelete'])->name('banners.delete')->middleware('checkPermission:delete');
});
// End Banner Route

// Start Documents Route
Route::prefix('documents')->group(function () {
    Route::get('/', [BackendController::class, 'documents'])->name('documents.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createDocument'])->name('documents.create')->middleware('checkPermission:create');
    Route::post('/savedocuments', [BackendController::class, 'savedocument'])->name('documents.savedocument')->middleware('checkPermission:create');
    Route::get('/{id}', [BackendController::class, 'editDocument'])->name('documents.edit')->middleware('checkPermission:edit');
    Route::post('/updatedocument', [BackendController::class, 'updatedocument'])->name('documents.updatedocument')->middleware('checkPermission:edit');
    Route::post('/status', [BackendController::class, 'documentstatus'])->name('documents.status')->middleware('checkPermission:edit');
    Route::post('/delete', [BackendController::class, 'documentsdelete'])->name('documents.delete')->middleware('checkPermission:delete');
});
// End Documents Route

// Start Ministers Route
Route::prefix('ministers')->group(function () {
    Route::get('/', [BackendController::class, 'ministers'])->name('ministers.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createminister'])->name('ministers.create')->middleware('checkPermission:create');
    Route::post('/createbanner', [BackendController::class, 'saveminister'])->name('ministers.createbanner')->middleware('checkPermission:create');
    Route::get('/{id}', [BackendController::class, 'editminister'])->name('ministers.edit')->middleware('checkPermission:edit');
    Route::post('/updatebanner', [BackendController::class, 'updateminister'])->name('ministers.updateminister')->middleware('checkPermission:edit');
    Route::post('/status', [BackendController::class, 'ministerstatus'])->name('ministers.status')->middleware('checkPermission:edit');
    Route::post('/delete', [BackendController::class, 'ministerdelete'])->name('ministers.delete')->middleware('checkPermission:delete');
});
// End Ministers Route

// Start Menus Route
Route::prefix('menus')->group(function () {
    Route::get('/', [BackendController::class, 'menus'])->name('menus.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createmenus'])->name('menus.create')->middleware('checkPermission:create');
    Route::post('/savemenu', [BackendController::class, 'savemenu'])->name('menus.savemenu')->middleware('checkPermission:create');
    Route::get('/{id}', [BackendController::class, 'editmenu'])->name('menus.edit')->middleware('checkPermission:edit');
    Route::post('/update', [BackendController::class, 'updatemenu'])->name('menus.updateMenu')->middleware('checkPermission:edit');
    Route::post('/status', [BackendController::class, 'menustatus'])->name('menus.status')->middleware('checkPermission:edit');
    Route::post('/delete', [BackendController::class, 'menudelete'])->name('menus.delete')->middleware('checkPermission:delete');
    Route::get('/child/{id}', [BackendController::class, 'childmenus'])->name('menus.child.list')->middleware('checkPermission:view');
});
// End Menus Route

// Start Module Route
Route::prefix('modules')->group(function () {
    Route::get('/', [BackendController::class, 'modules'])->name('modules.list')->middleware('checkPermission:view');
    Route::get('/child/{id}', [BackendController::class, 'childmodules'])->name('modules.child.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createmodules'])->name('modules.create')->middleware('checkPermission:create');
    Route::post('/savemodules', [BackendController::class, 'savemodules'])->name('modules.savemodules')->middleware('checkPermission:create');
    Route::get('/{id}', [BackendController::class, 'editmodules'])->name('modules.edit')->middleware('checkPermission:edit');
    Route::post('/update', [BackendController::class, 'updatemodule'])->name('modules.updatemodule')->middleware('checkPermission:edit');
    Route::post('/status', [BackendController::class, 'modulesstatus'])->name('modules.status')->middleware('checkPermission:edit');
    Route::post('/delete', [BackendController::class, 'modulesdelete'])->name('modules.delete')->middleware('checkPermission:delete');
});
// End Modules Route

// Start Users Route
Route::prefix('users')->group(function () {
    Route::get('/', [BackendController::class, 'users'])->name('users.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createuser'])->name('users.create')->middleware('checkPermission:create');
    Route::get('/{id}', [BackendController::class, 'usersByRole'])->name('users.roletype')->middleware('checkPermission:view');
    Route::post('/saveuser', [BackendController::class, 'saveuser'])->name('users.save')->middleware('checkPermission:create');
    Route::post('/status', [BackendController::class, 'userstatus'])->name('users.status')->middleware('checkPermission:edit');
});
// End Users Route
// Start Role Route
Route::prefix('role')->group(function () {
    Route::get('/', [BackendController::class, 'role'])->name('role.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createrole'])->name('role.create')->middleware('checkPermission:create');
    //Route::get('/{id}', [BackendController::class, 'usersByRole'])->name('role.roletype')->middleware('checkPermission:view');
    Route::post('/save', [BackendController::class, 'saverole'])->name('role.save')->middleware('checkPermission:create');
    Route::post('/status', [BackendController::class, 'rolestatus'])->name('role.status')->middleware('checkPermission:edit');
});
// End Role Route

// Start Permissions Route
Route::prefix('permissions')->group(function () {
    Route::get('/', [BackendController::class, 'permissionsList'])->name('permissions.list')->middleware('checkPermission:view');
    Route::get('/create', [BackendController::class, 'createpermission'])->name('permissions.create')->middleware('checkPermission:create');
    Route::post('/save', [BackendController::class, 'savepermissions'])->name('permissions.save')->middleware('checkPermission:create');
    Route::post('/status', [BackendController::class, 'permissionstatus'])->name('permissions.status')->middleware('checkPermission:edit');
});
// End Permissions Route
Route::prefix('applications')->group(function () {
    Route::get('/', [ApplicationController::class, 'applications'])->name('applications')->middleware('checkPermission:view');
    Route::get('/mapping', [ApplicationController::class, 'mapping'])->name('applications.maping')->middleware('checkPermission:view');
    // Route::post('/savepersonal', [ApplicationController::class, 'savepersonal'])->name('personal.create')->middleware('checkPermission:create');
    // Route::get('/foreign', [ApplicationController::class, 'foreign'])->name('foreign')->middleware('checkPermission:view');
    // Route::post('/saveforeign', [ApplicationController::class, 'saveforeign'])->name('foreign.save')->middleware('checkPermission:create');
    // Route::get('/employment', [ApplicationController::class, 'employment'])->name('employment')->middleware('checkPermission:view');
    // Route::post('/saveemployment', [ApplicationController::class, 'saveemployment'])->name('employment.save')->middleware('checkPermission:create');  
});
// Start Audit Trail & Logs Route
Route::prefix('logs')->group(function () {
    Route::get('/', [BackendController::class, 'logs'])->name('logs.list')->middleware('checkPermission:view');
    
});
// End Audit Trail & Logs Route