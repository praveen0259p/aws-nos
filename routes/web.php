<?php

use Illuminate\Support\Facades\Route;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
Route::get('/', [FrontController::class, 'index']);
Route::get('/login', [FrontController::class, 'showLoginForm'])->name('login');
Route::post('/login', [FrontController::class, 'login'])->name('login');
Route::get('/logout', [FrontController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/districts/{state}', [FrontController::class, 'getDistricts']);
Route::middleware(['registrationWindow'])->group(function () {
    Route::get('/register', [FrontController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [FrontController::class, 'register'])->name('student.register');
});    
Route::get('/activate-account/{email}', [FrontController::class, 'activate'])->name('activate.account');
//start common routes for showing document
Route::get('/scheme-guideline', [FrontController::class, 'document'])->name('guideline');
Route::get('/apply', [FrontController::class, 'document'])->name('apply');
Route::get('/faq', [FrontController::class, 'document'])->name('faq');
Route::get('/results', [FrontController::class, 'document'])->name('results');
Route::get('/forms', [FrontController::class, 'document'])->name('forms');
//end common routes for showing document
Route::get('/sitemap', [FrontController::class, 'sitemap'])->name('sitemap');
// start common route for content page 
Route::get('/contact-us', [FrontController::class, 'content'])->name('contact-us');
// end common route for content page 


Route::get('yajraData', [FrontController::class, 'yajraData'])->name('yajraData');
Route::middleware(['auth'])->group(base_path('routes/backend.php'));