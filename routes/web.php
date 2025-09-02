<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/child-safety', function () {
    return view('child-safety');
});
Route::get('/gia-privacy-policy', function () {
    return view('gia-privacy-policy');
});
Route::get('/gia-child-safety', function () {
    return view('gia-child-safety');
});