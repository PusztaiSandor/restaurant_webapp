<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CourierOrderController extends Controller
{
    /**
     * 📦 Futárhoz rendelt kiszállítási rendelések listázása
     */
    public function index()
    {

        $orders = Order::with(['user', 'items.dish'])
            ->where('courier_id', Auth::id())
            ->where('delivery_method', 'delivery')
            ->whereIn('status', ['atvetelre_kesz', 'kiszallitva', 'lezarva'])
            ->orderByDesc('created_at')
            ->get();

        return view('courier.orders', compact('orders'));
    }

    /**
     * 🚚 Rendelés státuszának módosítása „kiszállítva” értékre
     */
    public function markDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->courier_id !== Auth::id()) {
            return back()->with('error', 'Ez a rendelés nem hozzád tartozik.');
        }

        if ($order->status !== 'atvetelre_kesz') {
            return back()->with('error', 'Csak kiszállításra kész rendelést lehet lezárni.');
        }

        $order->status = 'kiszallitva';
        $order->save();

        return back()->with('success', 'A rendelés státusza „kiszállítva” lett.');
    }
}
