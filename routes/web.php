<?php

use Illuminate\Support\Facades\Route;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\BackendController;

Route::get('/', [FrontController::class, 'index']);
Route::get('/login', [FrontController::class, 'showLoginForm'])->name('login');
Route::post('/login', [FrontController::class, 'login'])->name('login');
Route::get('/logout', [FrontController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/register/{year}/{round}', [FrontController::class, 'showRegistrationForm'])->name('register');
Route::get('/districts/{state}', [FrontController::class, 'getDistricts']);
Route::post('/register', [FrontController::class, 'register'])->name('student.register');
Route::get('/activate-account/{email}', [FrontController::class, 'activate'])->name('activate.account');
Route::get('/scheme-guideline', [FrontController::class, 'schemeGuideline'])->name('guideline');
Route::get('/results', [FrontController::class, 'results'])->name('results');
Route::get('/forms', [FrontController::class, 'forms'])->name('forms');

Route::get('yajraData', [FrontController::class, 'yajraData'])->name('yajraData');

Route::get('/application', [BackendController::class, 'application'])->name('application');
Route::middleware(['auth'])->group(base_path('routes/backend.php'));