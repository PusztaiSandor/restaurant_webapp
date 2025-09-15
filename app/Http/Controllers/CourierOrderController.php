<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierOrderController extends Controller
{
    // Futárhoz rendelt kiszállítási rendelések listázása.


    public function index(Request $request)
    {

        $query = Order::with(['user', 'items.dish'])
            ->where('courier_id', Auth::id())
            ->where('delivery_method', 'delivery')
            ->whereIn('status', ['atvetelre_kesz', 'kiszallitva', 'lezarva', 'torolve']);

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('is_paid', true);
            } elseif ($request->payment_status === 'unpaid') {
                $query->where('is_paid', false);
            }
        }

        if ($request->filled('sort') && $request->sort === 'date_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->get();

        return view('courier.orders', compact('orders'));
    }

    // Rendelés státuszának módosítása „kiszállítva” értékre.

    public function markDelivered($orderId)
    {

        $order = Order::findOrFail($orderId);

        if ($order->courier_id !== Auth::id()) {
            return back()->with('error', 'Ez a rendelés nem hozzád tartozik.');
        }

        // Csak akkor módosítható, ha a rendelés státusza 'atvetelre_kesz' és ki van fizetve
    if ($order->status !== 'atvetelre_kesz' || !$order->is_paid) {
        return back()->with('error', 'Csak fizetett, kiszállításra kész rendelést lehet lezárni.');
    }

        $order->status = 'kiszallitva';
        $order->save();

        return back()->with('success', 'A rendelés státusza „Kiszállítva” lett.');
    }
}
