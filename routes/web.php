<?php

use Illuminate\Support\Facades\Route;

// 🔸 Kezdőlap – welcome.blade.php nézet
Route::get('/', function () {
    return view('welcome');
})->name('home');
