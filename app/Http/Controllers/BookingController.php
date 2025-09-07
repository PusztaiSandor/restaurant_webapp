<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Asztalfoglalás létrehozása – űrlap megjelenítése.
    // Csak akkor engedélyezett, ha a rendelés típusa 'dine-in' (helyben fogyasztás).
    // Lekérdezzük az összes foglalható asztalt, és átadjuk a nézetnek.
    public function create(Order $order)
    {
        if ($order->delivery_method !== 'dine-in') {
            abort(403);
        }

        $tables = Table::where('is_reservable', true)->get();

        return view('bookings.create', compact('order', 'tables'));
    }

    // Asztalfoglalás mentése az adatbázisba.
    // Validáljuk a beküldött adatokat, majd létrehozzuk a foglalást.

    public function store(Request $request)
    {

        // Beküldött adatok ellenőrzése:
        $request->validate([
            'orders_id' => 'required|exists:orders,orders_id',
            'seats' => 'required|integer|min:1',
            'table_code' => 'required|array|min:1', // Tömbként érkezik
            'table_code.*' => 'string|exists:tables,table_code', // Minden elem valid
        ]);
        // Rendelés lekérése az ID alapján
        $order = Order::findOrFail($request->orders_id);
        // Új foglalás létrehozása
        $booking = new Booking;
        $booking->users_id = Auth::id();
        $booking->orders_id = $order->orders_id;
        $booking->seats = $request->seats;

        // Több asztalkód összefűzése vesszővel
        $booking->table_code = implode(',', $request->table_code);

        // Foglalási idő = rendelés időpont + 2 óra
        $booking->booking_time = Carbon::parse($order->created_at)->addHours(2);
        $booking->status = 'uj'; // Alapértelmezett státusz: új foglalás
        $booking->save();

        // Visszairányítás a saját rendeléseim oldalra, sikeres üzenettel

        return redirect()->route('orders.myorders')->with('success', 'Foglalás sikeresen létrehozva.');
    }

    // Foglalás lemondása.
    // Csak a foglalás tulajdonosa mondhatja le.
    // Csak akkor törölhető, ha a rendelés még nem fizetett és korai státuszban van.

    public function cancel(Booking $booking)
    {

        // Jogosultság ellenőrzése: csak saját foglalás törölhető
        if ($booking->users_id !== Auth::id()) {
            abort(403);
        }
        // Csak akkor törölhető, ha a rendelés még nem fizetett és nem készült el
        if (in_array($booking->order->status, ['uj', 'keszul', 'atvetelre_kesz']) && ! $booking->order->is_paid) {
            $booking->status = 'torolve';
            $booking->save();
        }

        // Visszairányítás a saját rendeléseim oldalra
        return redirect()->route('orders.myorders')->with('success', 'Foglalás lemondva.');
    }

    // Foglalás státuszának frissítése (admin funkció).
    // Csak akkor módosítható, ha még 'uj' státuszban van.
    public function updateStatus(Request $request, Booking $booking)
    {
        // Csak admin végezheti
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        // Beküldött státusz validálása
        $request->validate([
            'status' => 'required|in:teljesitve,elutasitva',
        ]);

        // Csak akkor módosítható, ha még 'uj' státuszban van
        if ($booking->status === 'uj') {
            $booking->status = $request->status;
            $booking->save();
        }

        // Visszairányítás az előző oldalra, sikeres üzenettel
        return redirect()->back()->with('success', 'Asztalfoglalás státusza frissítve.');
    }
}
