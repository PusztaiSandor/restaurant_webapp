<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController; // 🧭 Felhasználói controller importálása

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

// 🔸 Profil főoldal – teljes szerkesztés és jelszómódosítás
Route::get('/mypage', [UserController::class, 'show'])->name('mypage');

// 🛠️ Csak e-mail és jelszó frissítése
Route::get('/mypage/edit', [UserController::class, 'edit'])->name('mypage.edit');

// 💾 Profiladatok frissítése
Route::post('/mypage/update', [UserController::class, 'update'])->name('mypage.update');

// 🔐 Jelszómódosítás
Route::post('/mypage/password', [UserController::class, 'updatePassword'])->name('mypage.password');

// 📦 Saját rendelések megtekintése
Route::get('/mypage/orders', [UserController::class, 'orders'])->name('mypage.orders');


