<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController; // 🧭 Felhasználói controller importálása
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DishController;

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

// 🛡️ Admin felhasználókezelés – csak admin jogosultsággal elérhető

// 📋 Felhasználók listázása
Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

// ➕ Új felhasználó létrehozása – űrlap megjelenítése
Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');

// 💾 Új felhasználó mentése
Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');

// ✏️ Felhasználó szerkesztése – űrlap megjelenítése
Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');

// 💾 Felhasználó adatainak frissítése
Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

// 🔄 Felhasználó aktiválása/inaktiválása
Route::patch('/admin/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');

// 🔁 Ideiglenes jelszó és email újragenerálása
Route::post('/admin/users/{user}/regenerate-password', [AdminUserController::class, 'regeneratePassword'])->name('admin.users.regeneratePassword');

// 🍽️ Publikus étlap megtekintése
Route::get('/menu', [DishController::class, 'menu'])->name('menu');

// 📄 PDF generálása az étlapból
Route::get('/menu/pdf', [DishController::class, 'generateMenuPdf'])->name('menu.pdf');

// 🔍 Egy adott étel részletes nézete
Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');

// 🛡️ Admin ételkezelés – csak admin jogosultsággal

// 📋 Ételek listázása (aktív + archivált)
Route::get('/admin/dishes', [DishController::class, 'index'])->name('admin.dishes.index');

// ➕ Új étel létrehozása – űrlap megjelenítése
Route::get('/admin/dishes/create', [DishController::class, 'create'])->name('admin.dishes.create');

// 💾 Új étel mentése
Route::post('/admin/dishes', [DishController::class, 'store'])->name('admin.dishes.store');

// ✏️ Étel szerkesztése – űrlap megjelenítése
Route::get('/admin/dishes/{dish}/edit', [DishController::class, 'edit'])->name('admin.dishes.edit');

// 💾 Étel frissítése
Route::put('/admin/dishes/{dish}', [DishController::class, 'update'])->name('admin.dishes.update');

// 🔄 Étel aktiválása
Route::patch('/admin/dishes/{dish}/activate', [DishController::class, 'activate'])->name('admin.dishes.activate');

// 🗃️ Étel archiválása
Route::patch('/admin/dishes/{dish}/deactivate', [DishController::class, 'deactivate'])->name('admin.dishes.deactivate');

// 📦 Készlet szerkesztése – űrlap megjelenítése
Route::get('/admin/dishes/{dish}/stock', [DishController::class, 'editStock'])->name('admin.dishes.editStock');

// 💾 Készlet frissítése
Route::patch('/admin/dishes/{dish}/stock', [DishController::class, 'updateStock'])->name('admin.dishes.updateStock');


