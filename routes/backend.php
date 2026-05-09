<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackendController;

Route::get('/dashboard', [BackendController::class, 'dashboard'])->name('dashboard');
//Route::get('/application', [BackendController::class, 'application'])->name('application');