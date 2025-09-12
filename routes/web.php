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

// Elfelejtett jelszó – e-mail bekérése
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'simulateReset'])->name('forgot-password.post');

// Szimulált e-mail nézet
Route::get('/simulated-email/{email}', [AuthController::class, 'simulateReset'])->name('simulated-email');

// Jelszó visszaállító űrlap
Route::get('/reset-password/{email}', [AuthController::class, 'showResetForm'])->name('confirm-reset');

// Jelszó frissítése
Route::post('/reset-password/{email}', [AuthController::class, 'updatePassword'])->name('confirm-reset.post');

// Kilépés – POST metódus, visszairányítással
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profil főoldal – teljes szerkesztés és jelszómódosítás
Route::get('/profile', [UserController::class, 'show'])->name('profile');
Route::get('/profile/mypage-edit', [UserController::class, 'editMypage'])->name('profile.mypage.edit');

// Csak e-mail és jelszó frissítése
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::post('/profile/credentials', [UserController::class, 'updateCredentials'])->name('profile.credentials');

// Profiladatok frissítése
Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');

// Jelszómódosítás
Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');

// Admin felhasználókezelés – csak admin jogosultsággal elérhető

// Felhasználók listázása
Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

// Új felhasználó létrehozása – űrlap megjelenítése
Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');

// Új felhasználó mentése
Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');

// Felhasználó szerkesztése – űrlap megjelenítése
Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');

// Felhasználó adatainak frissítése
Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

// Felhasználó aktiválása/inaktiválása
Route::patch('/admin/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');

// Ideiglenes jelszó és email újragenerálása
Route::post('/admin/users/{user}/regenerate-password', [AdminUserController::class, 'regeneratePassword'])->name('admin.users.regeneratePassword');


// Publikus étlap megtekintése
Route::get('/menu', [DishController::class, 'menu'])->name('menu');

// Étlap megjelenítése
// Route::get('/menu', [DishController::class, 'menu'])->name('menu');

// Egy adott étel részletes nézete
Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');

// Egy adott étel részleteinek megjelenítése
// Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');


// Admin ételkezelés – csak admin jogosultsággal
// Ételek listázása (aktív + archivált)
Route::get('/admin/dishes', [DishController::class, 'index'])->name('admin.dishes.index');

// Új étel létrehozása – űrlap megjelenítése
Route::get('/admin/dishes/create', [DishController::class, 'create'])->name('admin.dishes.create');

// Új étel mentése
Route::post('/admin/dishes', [DishController::class, 'store'])->name('admin.dishes.store');

// Étel szerkesztése – űrlap megjelenítése
Route::get('/admin/dishes/{dish}/edit', [DishController::class, 'edit'])->name('admin.dishes.edit');

// Étel frissítése
Route::put('/admin/dishes/{dish}', [DishController::class, 'update'])->name('admin.dishes.update');

// Étel aktiválása
Route::put('/admin/dishes/{dish}/activate', [DishController::class, 'activate'])->name('admin.dishes.activate');

// Étel archiválása
Route::put('/admin/dishes/{dish}/deactivate', [DishController::class, 'deactivate'])->name('admin.dishes.deactivate');

// Készlet szerkesztése – űrlap megjelenítése
Route::get('/admin/dishes/{dish}/stock', [DishController::class, 'editStock'])->name('admin.dishes.editStock');

// Készlet frissítése
Route::put('/admin/dishes/{dish}/stock', [DishController::class, 'updateStock'])->name('admin.dishes.stock.update');


// Globális díjak admin útvonalai
// Megjeleníti az összes aktív és inaktív díjtételt (pl. kiszállítási díj, szervízdíj)
Route::get('admin/global-charges', [GlobalChargeController::class, 'index'])->name('global-charges.index');
// Új globális díjtétel létrehozása
Route::get('admin/global-charges/create', [GlobalChargeController::class, 'create'])->name('global-charges.create');
// Új díjtétel mentése az adatbázisba
Route::post('admin/global-charges', [GlobalChargeController::class, 'store'])->name('global-charges.store');
// Meglévő díjtétel szerkesztése
Route::get('admin/global-charges/{id}/edit', [GlobalChargeController::class, 'edit'])->name('global-charges.edit');
// Díjtétel frissítése
Route::put('admin/global-charges/{id}', [GlobalChargeController::class, 'update'])->name('global-charges.update');
// Díjtétel törlése
Route::delete('admin/global-charges/{id}', [GlobalChargeController::class, 'destroy'])->name('global-charges.destroy');

// Gyors kosárba helyezés
Route::post('/cart/quick-add/{id}', [CartController::class, 'quickAdd'])->name('cart.quickAdd');
// Ételt gyorsan kosárba helyezni (duplikált útvonal, egységesítés javasolt)
Route::post('/cart/quick-add/{dish}', [CartController::class, 'quickAdd'])->name('cart.quickAdd');

// Ételt kosárhoz adni (méret, extrák alapján)
Route::post('/order/add/{dish}', [CartController::class, 'add'])->name('order.add');

// Kosár megtekintése
Route::get('orders/cart', [CartController::class, 'index'])->name('cart.index');

// Kosárban lévő tétel mennyiségének növelése
Route::post('/cart/increase/{key}', [CartController::class, 'increase'])->name('order.increase');
// Kosárban lévő tétel mennyiségének csökkentése
Route::post('/cart/decrease/{key}', [CartController::class, 'decrease'])->name('order.decrease');
// Tétel eltávolítása a kosárból
Route::post('/cart/remove/{key}', [CartController::class, 'remove'])->name('order.remove');
// Kosár tartalmának megtekintése
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Kosár teljes kiürítése
Route::post('/order/clear', [CartController::class, 'clear'])->name('order.clear');
// Fizetési oldal megjelenítése
Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
// Rendelés véglegesítése
Route::post('/submit-order', [OrderController::class, 'submit'])->name('order.submit');




// Saját rendelések megtekintése
Route::get('/orders/myorders', [OrderController::class, 'myOrders'])->name('orders.myorders');
// Rendelés lemondása
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');

// Admin felület: rendelések listázása
Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
// Admin: rendelés státuszának frissítése
Route::post('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updatestatus');
// Admin: futár hozzárendelése rendeléshez
Route::post('/admin/orders/{order}/assign-courier', [AdminOrderController::class, 'assignCourier'])->name('admin.orders.assignCourier');

// Futár rendelései listázása
Route::get('/courier/orders', [CourierOrderController::class, 'index'])->name('courier.orders.index');

// Futár státuszváltása „kiszállítva” értékre
Route::post('/courier/orders/{order}/delivered', [CourierOrderController::class, 'markDelivered'])->name('courier.orders.markDelivered');


// Fizetési felület megjelenítése adott rendeléshez
Route::get('/orders/{order}/pay', [OrderController::class, 'showPaymentForm'])->name('order.pay');
// Fizetés szimulálása (pl. teszteléshez)
Route::post('/orders/{order}/pay', [OrderController::class, 'simulatePayment'])->name('order.pay.submit');


// Admin: asztalok listázása
Route::get('/admin/tables', [AdminTableController::class, 'index'])->name('admin.tables.index');
// Admin: új asztal létrehozása
Route::get('/admin/tables/create', [AdminTableController::class, 'create'])->name('admin.tables.create');
// Admin: új asztal mentése
Route::post('/admin/tables/store', [AdminTableController::class, 'store'])->name('admin.tables.store');
// Admin: asztal szerkesztése
Route::get('/admin/tables/{table}/edit', [AdminTableController::class, 'edit'])->name('admin.tables.edit');
// Admin: asztal frissítése
Route::put('/admin/tables/{table}', [AdminTableController::class, 'update'])->name('admin.tables.update');

// Foglalás létrehozása rendeléshez
Route::get('/bookings/create/{order}', [BookingController::class, 'create'])->name('bookings.create');
// Foglalás mentése
Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
// Foglalás lemondása
Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
// Admin: foglalás státuszának frissítése
Route::post('/admin/bookings/{booking}/updatestatus', [BookingController::class, 'updateStatus'])->name('admin.bookings.updatestatus');


// Rendelés értékelése (csillag + szöveg)
Route::post('/orders/{order}/rate', [OrderController::class, 'rate'])->name('order.rate');
// Kapcsolatfelvételi űrlap elküldése
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
// Kapcsolatfelvételi oldal megjelenítése
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Általános felhasználási feltételek megjelenítése
Route::get('/terms', [ContactController::class, 'terms'])->name('terms');

// Étlap PDF exportálása
Route::get('/menu/pdf', [DishController::class, 'exportPdf'])->name('menu.pdf');
// PDF generálása az étlapból
Route::get('/menu/pdf', [DishController::class, 'generateMenuPdf'])->name('menu.pdf');

// PDF letöltés útvonala
Route::get('/menu/pdf', [MenuController::class, 'downloadPdf'])->name('menu.pdf');

// Rendeléshez tartozó számla letöltése PDF-ben
Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('order.invoice');

// Admin: visszajelzések megtekintése
Route::get('/admin/feedbacks', [ContactController::class, 'adminFeedbacks'])->name('admin.feedbacks');
