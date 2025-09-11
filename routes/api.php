<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Application cache, config, route, and view cleared!';
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
Route::post('/contact', [AuthController::class, 'Contacts']);
Route::post('/stepsCheck',[ApiController::class,'stepsCheck']);
Route::post('/faq', [AuthController::class, 'FAQ']);
Route::get('/version', [AuthController::class, 'Version']);

Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    Route::post('/dashboard', [AuthController::class, 'dashboard']);
    Route::post('/GetProposalListShrest', [AuthController::class, 'GetProposalListShrest']);
    Route::post('/steps',[ApiController::class,'steps']);
    Route::post('/getform',[ApiController::class,'getform']);
    Route::post('/dropdown',[ApiController::class,'dropdown']);
    Route::post('/submit',[ApiController::class,'submit']);
    Route::post('/schemes',[AuthController::class,'schemes']);
    Route::post('/menus',[AuthController::class,'menus']);
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::post('/statefilter', [AuthController::class, 'StateFilter']);
    Route::post('/districtfilter', [AuthController::class, 'DistrictFilter']);
    Route::post('/getPhotoFields', [AuthController::class, 'getPhotoFields']);
    Route::post('/preview', [AuthController::class, 'preview']);
    Route::post('/photo', [AuthController::class, 'photo']);
    Route::post('/submissionData', [AuthController::class, 'submissionData']);
});

Route::post('/cronData', [AuthController::class, 'cronData'])->middleware('ip.restrict');;