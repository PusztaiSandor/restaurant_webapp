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
    //Foglalás létrehozása

    public function create(Order $order)
    {
        if ($order->delivery_method !== 'dine-in') {
            abort(403);
        }

        $tables = Table::where('is_reservable', true)->get();

        return view('bookings.create', compact('order', 'tables'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'orders_id' => 'required|exists:orders,orders_id',
            'seats' => 'required|integer|min:1',
            'table_code' => 'required|array|min:1', // Tömbként érkezik
            'table_code.*' => 'string|exists:tables,table_code',
        ]);

        $order = Order::findOrFail($request->orders_id);

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

        return redirect()->route('orders.myorders')->with('success', 'Foglalás sikeresen létrehozva.');
    }

    // Foglalás lemondása
    // Csak akkor törölhető, ha a rendelés még nem fizetett és korai státuszban van.

    public function cancel(Booking $booking)
    {

        if ($booking->users_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($booking->order->status, ['uj', 'keszul', 'atvetelre_kesz']) && ! $booking->order->is_paid) {
            $booking->status = 'torolve';
            $booking->save();
        }

        return redirect()->route('orders.myorders')->with('success', 'Foglalás lemondva.');
    }

//Státusz frissítése admin által

    public function updateStatus(Request $request, Booking $booking)
    {

        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:teljesitve,elutasitva',
        ]);


        if ($booking->status === 'uj') {
            $booking->status = $request->status;
            $booking->save();
        }

        return redirect()->back()->with('success', 'Asztalfoglalás státusza frissítve.');
    }
}
