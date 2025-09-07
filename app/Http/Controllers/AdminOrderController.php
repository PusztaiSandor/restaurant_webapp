<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Összes rendelés listázása az admin felületen.

    public function index(Request $request)
    {
        // Lekérdezzük az összes futárt, hogy később hozzárendelhetőek legyenek a rendeléshez
        $couriers = \App\Models\User::where('role', 'courier')->get();

        // Előkészítjük az alap lekérdezést, betöltjük a kapcsolódó adatokat is (user, items, dish, booking)
        $query = Order::with(['user', 'items.dish', 'booking']);

        // Rendelés státusz szűrés
        if ($request->filled('status') && $request->status !== 'mind') {
            $query->where('status', $request->status);
        }

        // Átvételi mód szűrés
        if ($request->filled('delivery_method') && $request->delivery_method !== 'mind') {
            $query->where('delivery_method', $request->delivery_method);
        }

        // Fizetési állapot szűrés
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('is_paid', true);
            } elseif ($request->payment_status === 'unpaid') {
                $query->where('is_paid', false);
            }
        }

        // Asztalfoglalás státusz szűrés
        if ($request->filled('booking_status') && $request->booking_status !== 'mind') {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('status', $request->booking_status);
            });
        }

        // Rendezés: ha kérve van, akkor dátum szerint növekvő sorrendben, egyébként csökkenőben
        if ($request->filled('sort') && $request->sort === 'date_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Lekérdezzük az összes szűrt rendelést
        $orders = $query->get();

        // Visszatérünk az admin rendeléslista nézethez, átadva a rendelések és futárok adatait
        return view('admin.orders.index', compact('orders', 'couriers'));
    }

    // Egy konkrét rendelés részleteinek megtekintése.
    // Betöltjük a felhasználót és a rendelt ételeket is.

    public function show($orderId)
    {
        $order = Order::with(['user', 'items.dish'])->findOrFail($orderId);

        // Visszatérünk a részletes rendelésnézethez
        return view('admin.orders.show', compact('order'));
    }

    // Rendelés státuszának módosítása.
    // Csak előre definiált státuszok engedélyezettek.

    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Ellenőrizzük, hogy a beküldött státusz érvényes-e
        $request->validate([
            'status' => 'required|in:uj,keszul,atvetelre_kesz,atvetel_megtortent,kiszallitva,lezarva',
        ]);

        // Frissítjük a rendelés státuszát
        $order->status = $request->status;
        $order->save();

        // Visszajelzés az adminnak
        return back()->with('success', 'Státusz frissítve.');
    }

    // Futár hozzárendelése egy rendeléshez.
    // Csak akkor engedélyezett, ha a rendelés kiszállításra kész.
    public function assignCourier(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        // Ellenőrizzük, hogy a rendelés kiszállításra kész-e
        if ($order->delivery_method !== 'delivery' || $order->status !== 'atvetelre_kesz') {
            return back()->with('error', 'Csak kiszállításra kész rendelés rendelhető futárhoz.');
        }

        // Ellenőrizzük, hogy a futár id érvényes-e
        $request->validate([
            'courier_id' => 'required|exists:users,users_id',
        ]);

        // Hozzárendeljük a futárt a rendeléshez
        $order->courier_id = $request->courier_id;
        $order->save();

        // Visszajelzés az adminnak
        return back()->with('success', 'Rendelés hozzárendelve a futárhoz.');
    }
}
