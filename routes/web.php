<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\GlobalChargeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CourierOrderController;
use App\Http\Controllers\AdminTableController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;

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
Route::get('/profile', [UserController::class, 'show'])->name('profile');

// 🛠️ Csak e-mail és jelszó frissítése
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::post('/profile/credentials', [UserController::class, 'updateCredentials'])->name('profile.credentials');

// 💾 Profiladatok frissítése
Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');



// 🔐 Jelszómódosítás
Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

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
Route::put('/admin/dishes/{dish}/activate', [DishController::class, 'activate'])->name('admin.dishes.activate');

// 🗃️ Étel archiválása
Route::put('/admin/dishes/{dish}/deactivate', [DishController::class, 'deactivate'])->name('admin.dishes.deactivate');

// 📦 Készlet szerkesztése – űrlap megjelenítése
Route::get('/admin/dishes/{dish}/stock', [DishController::class, 'editStock'])->name('admin.dishes.editStock');

// 💾 Készlet frissítése
Route::put('/admin/dishes/{dish}/stock', [DishController::class, 'updateStock'])->name('admin.dishes.stock.update');

// 🏠 Étlap
Route::get('/menu', [DishController::class, 'menu'])->name('menu');

// 📄 Étlap PDF export
Route::get('/menu/pdf', [DishController::class, 'exportPdf'])->name('menu.pdf');

// 🍽️ Egy adott étel részletei
Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');

// 🛒 Gyors kosárba helyezés
Route::post('/cart/quick-add/{id}', [App\Http\Controllers\CartController::class, 'quickAdd'])->name('cart.quickAdd');

// Globális díjak admin útvonalai
Route::get('admin/global-charges', [GlobalChargeController::class, 'index'])->name('global-charges.index');
Route::get('admin/global-charges/create', [GlobalChargeController::class, 'create'])->name('global-charges.create');
Route::post('admin/global-charges', [GlobalChargeController::class, 'store'])->name('global-charges.store');
Route::get('admin/global-charges/{id}/edit', [GlobalChargeController::class, 'edit'])->name('global-charges.edit');
Route::put('admin/global-charges/{id}', [GlobalChargeController::class, 'update'])->name('global-charges.update');
Route::delete('admin/global-charges/{id}', [GlobalChargeController::class, 'destroy'])->name('global-charges.destroy');

Route::get('orders/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/quick-add/{dish}', [CartController::class, 'quickAdd'])->name('cart.quickAdd');

Route::post('/order/add/{dish}', [CartController::class, 'add'])->name('order.add');

Route::post('/cart/increase/{key}', [CartController::class, 'increase'])->name('order.increase');
Route::post('/cart/decrease/{key}', [CartController::class, 'decrease'])->name('order.decrease');
Route::post('/cart/remove/{key}', [CartController::class, 'remove'])->name('order.remove');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/order/clear', [CartController::class, 'clear'])->name('order.clear');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/submit-order', [OrderController::class, 'submit'])->name('order.submit');

// 📦 Saját rendelések megtekintése
Route::get('/orders/myorders', [App\Http\Controllers\OrderController::class, 'myOrders'])->name('orders.myorders');

Route::post('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('order.cancel');

Route::get('/admin/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::post('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updatestatus');

Route::post('/admin/orders/{order}/assign-courier', [AdminOrderController::class, 'assignCourier'])->name('admin.orders.assignCourier');

// Futár rendelései listázása
Route::get('/courier/orders', [CourierOrderController::class, 'index'])->name('courier.orders.index');

// Futár státuszváltása „kiszállítva” értékre
Route::post('/courier/orders/{order}/delivered', [CourierOrderController::class, 'markDelivered'])->name('courier.orders.markDelivered');

Route::get('/orders/{order}/pay', [OrderController::class, 'showPaymentForm'])->name('order.pay');
Route::post('/orders/{order}/pay', [OrderController::class, 'simulatePayment'])->name('order.pay.submit');

Route::get('/admin/tables', [AdminTableController::class, 'index'])->name('admin.tables.index');
Route::get('/admin/tables/create', [AdminTableController::class, 'create'])->name('admin.tables.create');
Route::post('/admin/tables/store', [AdminTableController::class, 'store'])->name('admin.tables.store');
Route::get('/admin/tables/{table}/edit', [AdminTableController::class, 'edit'])->name('admin.tables.edit');
Route::put('/admin/tables/{table}', [AdminTableController::class, 'update'])->name('admin.tables.update');

Route::get('/bookings/create/{order}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

Route::post('/admin/bookings/{booking}/updatestatus', [BookingController::class, 'updateStatus'])->name('admin.bookings.updatestatus');

Route::post('/orders/{order}/rate', [OrderController::class, 'rate'])->name('order.rate');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/terms', [ContactController::class, 'terms'])->name('terms');

// 📄 PDF letöltés útvonala
Route::get('/menu/pdf', [MenuController::class, 'downloadPdf'])->name('menu.pdf');

Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('order.invoice');







