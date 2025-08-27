<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class AdminOrderController extends Controller
{
    /**
     * Összes rendelés listázása
     */
    // public function index()
    // {
    //     $couriers = \App\Models\User::where('role', 'courier')->get();
    //     $orders = Order::with(['user', 'items.dish'])->orderByDesc('created_at')->get();

    //     return view('admin.orders.index', compact('orders', 'couriers'));
    // }

    public function index(Request $request)
{
    $couriers = \App\Models\User::where('role', 'courier')->get();

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

    // Rendezés
    if ($request->filled('sort') && $request->sort === 'date_asc') {
        $query->orderBy('created_at', 'asc');
    } else {
        $query->orderBy('created_at', 'desc');
    }

    $orders = $query->get();

    return view('admin.orders.index', compact('orders', 'couriers'));
}

    /**
     * Egy rendelés részleteinek megtekintése
     */
    public function show($orderId)
    {
        $order = Order::with(['user', 'items.dish'])->findOrFail($orderId);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Rendelés státuszának módosítása (opcionális)
     */
    public function updateStatus(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);

    $request->validate([
        'status' => 'required|in:uj,keszul,atvetelre_kesz,atvetel_megtortent,kiszallitva,lezarva',
    ]);

    $order->status = $request->status;
    $order->save();

    return back()->with('success', 'Státusz frissítve.');
}

public function assignCourier(Request $request, $orderId)
{



    $order = Order::findOrFail($orderId);

    if ($order->delivery_method !== 'delivery' || $order->status !== 'atvetelre_kesz') {
        return back()->with('error', 'Csak kiszállításra kész rendelés rendelhető futárhoz.');
    }

    $request->validate([
        'courier_id' => 'required|exists:users,users_id',
    ]);

    $order->courier_id = $request->courier_id;
    $order->save();

    return back()->with('success', 'Rendelés hozzárendelve a futárhoz.');
}

}
