<?php

use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminTableController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourierOrderController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\GlobalChargeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Kezdőlap – welcome.blade.php nézet
Route::get('/', [HomeController::class, 'welcome'])->name('home');

// Regisztráció
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Bejelentkezés
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Elfelejtett jelszó
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'simulateReset'])->name('forgot-password.post');

// Szimulált e-mail nézet
Route::get('/simulated-email/{email}', [AuthController::class, 'simulateReset'])->name('simulated-email');

// Jelszó módosító űrlap
Route::get('/reset-password/{email}', [AuthController::class, 'showResetForm'])->name('confirm-reset');

// Jelszó frissítése
Route::post('/reset-password/{email}', [AuthController::class, 'updatePassword'])->name('confirm-reset.post');

// Kilépés
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profil oldalak, szerkesztés, módosítás
Route::get('/profile', [UserController::class, 'show'])->name('profile');
Route::get('/profile/mypage-edit', [UserController::class, 'editMypage'])->name('profile.mypage.edit');

Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::post('/profile/credentials', [UserController::class, 'updateCredentials'])->name('profile.credentials');

Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');

Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

// Admin felhasználókezelés – csak admin jogosultsággal elérhető

Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');

Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');

Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');

Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

Route::patch('/admin/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');

// Ideiglenes jelszó és email újragenerálása
Route::post('/admin/users/{user}/regenerate-password', [AdminUserController::class, 'regeneratePassword'])->name('admin.users.regeneratePassword');


// Publikus étlap és a részletek megtekintése
Route::get('/menu', [DishController::class, 'menu'])->name('menu');

Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');


// Admin ételkezelés

Route::get('/admin/dishes', [DishController::class, 'index'])->name('admin.dishes.index');

Route::get('/admin/dishes/create', [DishController::class, 'create'])->name('admin.dishes.create');

Route::post('/admin/dishes', [DishController::class, 'store'])->name('admin.dishes.store');

Route::get('/admin/dishes/{dish}/edit', [DishController::class, 'edit'])->name('admin.dishes.edit');

Route::put('/admin/dishes/{dish}', [DishController::class, 'update'])->name('admin.dishes.update');

Route::put('/admin/dishes/{dish}/activate', [DishController::class, 'activate'])->name('admin.dishes.activate');

Route::put('/admin/dishes/{dish}/deactivate', [DishController::class, 'deactivate'])->name('admin.dishes.deactivate');

Route::get('/admin/dishes/{dish}/stock', [DishController::class, 'editStock'])->name('admin.dishes.editStock');

Route::put('/admin/dishes/{dish}/stock', [DishController::class, 'updateStock'])->name('admin.dishes.stock.update');


// Admin Globális díjak kezelése

Route::get('admin/global-charges', [GlobalChargeController::class, 'index'])->name('global-charges.index');

Route::get('admin/global-charges/create', [GlobalChargeController::class, 'create'])->name('global-charges.create');

Route::post('admin/global-charges', [GlobalChargeController::class, 'store'])->name('global-charges.store');

Route::get('admin/global-charges/{id}/edit', [GlobalChargeController::class, 'edit'])->name('global-charges.edit');

Route::put('admin/global-charges/{id}', [GlobalChargeController::class, 'update'])->name('global-charges.update');


// Ételek kosárba helyezéseGyors kosárba helyezés

Route::post('/cart/quick-add/{dish}', [CartController::class, 'quickAdd'])->name('cart.quickAdd');

Route::post('/order/add/{dish}', [CartController::class, 'add'])->name('order.add');

// Kosár megtekintése, kosár funkciók
Route::get('orders/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/increase/{key}', [CartController::class, 'increase'])->name('order.increase');

Route::post('/cart/decrease/{key}', [CartController::class, 'decrease'])->name('order.decrease');

Route::post('/cart/remove/{key}', [CartController::class, 'remove'])->name('order.remove');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/order/clear', [CartController::class, 'clear'])->name('order.clear');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');

Route::post('/submit-order', [OrderController::class, 'submit'])->name('order.submit');


// Saját rendelések megtekintése, rendelés lemondása
Route::get('/orders/myorders', [OrderController::class, 'myOrders'])->name('orders.myorders');

Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');

// Admin felület, rendelések listázása, frissítése, futárhoz rendelés
Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');

Route::post('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updatestatus');

Route::post('/admin/orders/{order}/assign-courier', [AdminOrderController::class, 'assignCourier'])->name('admin.orders.assignCourier');

// Futár rendelései listázása, státuszváltás
Route::get('/courier/orders', [CourierOrderController::class, 'index'])->name('courier.orders.index');

Route::post('/courier/orders/{order}/delivered', [CourierOrderController::class, 'markDelivered'])->name('courier.orders.markDelivered');


// Fizetési felület megjelenítése, fizetés szimulálása
Route::get('/orders/{order}/pay', [OrderController::class, 'showPaymentForm'])->name('order.pay');

Route::post('/orders/{order}/pay', [OrderController::class, 'simulatePayment'])->name('order.pay.submit');


// Admin, asztalok listázása, szerkesztése
Route::get('/admin/tables', [AdminTableController::class, 'index'])->name('admin.tables.index');

Route::get('/admin/tables/create', [AdminTableController::class, 'create'])->name('admin.tables.create');

Route::post('/admin/tables/store', [AdminTableController::class, 'store'])->name('admin.tables.store');

Route::get('/admin/tables/{table}/edit', [AdminTableController::class, 'edit'])->name('admin.tables.edit');

Route::put('/admin/tables/{table}', [AdminTableController::class, 'update'])->name('admin.tables.update');

// Foglalás létrehozása, mentése, lemondása, státuszkezelés
Route::get('/bookings/create/{order}', [BookingController::class, 'create'])->name('bookings.create');

Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');

Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

Route::post('/admin/bookings/{booking}/updatestatus', [BookingController::class, 'updateStatus'])->name('admin.bookings.updatestatus');


// Rendelés értékelése, kapcsolatfelvétel
Route::post('/orders/{order}/rate', [OrderController::class, 'rate'])->name('order.rate');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/terms', [ContactController::class, 'terms'])->name('terms');

// Étlap PDF exportálása, letöltése
Route::get('/menu/pdf', [DishController::class, 'exportPdf'])->name('menu.pdf');

Route::get('/menu/pdf', [DishController::class, 'generateMenuPdf'])->name('menu.pdf');

Route::get('/menu/pdf', [MenuController::class, 'downloadPdf'])->name('menu.pdf');

// Rendeléshez tartozó számla letöltése PDF-ben
Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('order.invoice');

// Admin: visszajelzések megtekintése
Route::get('/admin/feedbacks', [ContactController::class, 'adminFeedbacks'])->name('admin.feedbacks');
