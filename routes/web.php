<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// 🔸 Kezdőlap – welcome.blade.php nézet
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 📝 Regisztráció
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// 🔐 Bejelentkezés
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// ❓ Elfelejtett jelszó – e-mail bekérése
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'simulateReset'])->name('forgot-password.post');

// 📩 Szimulált e-mail nézet
Route::get('/simulated-email/{email}', [AuthController::class, 'simulateReset'])->name('simulated-email');

// 🔁 Jelszó visszaállító űrlap
Route::get('/reset-password/{email}', [AuthController::class, 'showResetForm'])->name('confirm-reset');

// 🔄 Jelszó frissítése
Route::post('/reset-password/{email}', [AuthController::class, 'updatePassword'])->name('confirm-reset.post');

// 🔓 Kilépés – POST metódus, visszairányítással
Route::post('/logout', function () {
    Auth::logout(); // Felhasználó kijelentkeztetése
    return redirect()->route('login')->with('success', 'Sikeresen kiléptél.');
})->name('logout');
