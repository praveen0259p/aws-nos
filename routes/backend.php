<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
Route::get('/dashboard', [BackendController::class, 'dashboard'])->name('dashboard');
// move this route to backend after testing
Route::middleware(['applicationWindow'])->group(function () {
    Route::get('/application', [BackendController::class, 'application'])->name('application');
    Route::post('/register/step/1',[BackendController::class, 'step1'])->name('submit.step1');
    Route::post('/register/step/2', fn(Request $r) =>
        session(['step2' => $r->only('street','city')])
    );
    Route::post('/register/step/3', fn(Request $r) =>
        session(['step3' => $r->only('card_number','expiry')])
    );
}); 