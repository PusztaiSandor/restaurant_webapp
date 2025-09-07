<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierOrderController extends Controller
{
    // Futárhoz rendelt kiszállítási rendelések listázása.
    // Csak az Auth::id() által azonosított futárhoz tartozó rendelések jelennek meg.
    // Lehetőség van státusz és fizetési állapot szerinti szűrésre, valamint idő szerinti rendezésre.

    public function index(Request $request)
    {

        // Alap lekérdezés: csak a bejelentkezett futárhoz tartozó rendelések,
        // amelyek kiszállításra vonatkoznak, és releváns státuszban vannak.
        $query = Order::with(['user', 'items.dish'])
            ->where('courier_id', Auth::id())
            ->where('delivery_method', 'delivery')
            ->whereIn('status', ['atvetelre_kesz', 'kiszallitva', 'lezarva', 'torolve']);

        // Rendelés státusz szűrés
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Fizetési állapot szűrés
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->where('is_paid', true);
            } elseif ($request->payment_status === 'unpaid') {
                $query->where('is_paid', false);
            }
        }

        // Rendezés a kiválasztott szempont szerint
        if ($request->filled('sort') && $request->sort === 'date_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        // Lekérdezett rendelések átadása a nézetnek
        $orders = $query->get();

        return view('courier.orders', compact('orders'));
    }

    // Rendelés státuszának módosítása „kiszállítva” értékre.
    // Csak akkor engedélyezett, ha a rendelés valóban a futárhoz tartozik,
    // és státusza 'atvetelre_kesz'.

    public function markDelivered($orderId)
    {
        // Rendelés lekérése az id alapján
        $order = Order::findOrFail($orderId);
        // Jogosultság ellenőrzése: csak saját rendelés módosítható, ilyen nem lehet
        if ($order->courier_id !== Auth::id()) {
            return back()->with('error', 'Ez a rendelés nem hozzád tartozik.');
        }
        // Csak akkor módosítható, ha a rendelés státusza 'atvetelre_kesz'
        if ($order->status !== 'atvetelre_kesz') {
            return back()->with('error', 'Csak kiszállításra kész rendelést lehet lezárni.');
        }
        // Státusz frissítése 'kiszallitva' értékre
        $order->status = 'kiszallitva';
        $order->save();

        // Visszairányítás sikeres üzenettel.
        return back()->with('success', 'A rendelés státusza „Kiszállítva” lett.');
    }
}
