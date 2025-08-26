<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
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
        'table_code.*' => 'string|exists:tables,table_code', // Minden elem valid
    ]);

    $order = Order::findOrFail($request->orders_id);

    $booking = new Booking();
    $booking->users_id = Auth::id();
    $booking->orders_id = $order->orders_id;
    $booking->seats = $request->seats;

    // Több asztalkód összefűzése vesszővel
    $booking->table_code = implode(',', $request->table_code);

    // Foglalási idő = rendelés időpont + 2 óra
    $booking->booking_time = Carbon::parse($order->created_at)->addHours(2);
    $booking->status = 'uj';
    $booking->save();

    return redirect()->route('orders.myorders')->with('success', 'Foglalás sikeresen létrehozva.');
}

    public function cancel(Booking $booking)
    {
        if ($booking->users_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($booking->order->status, ['uj','keszul','atvetelre_kesz']) && !$booking->order->is_paid) {
            $booking->status = 'torolve';
            $booking->save();
        }

        return redirect()->route('orders.myorders')->with('success', 'Foglalás lemondva.');
    }

    public function updateStatus(Request $request, Booking $booking)
{
    // Csak admin végezheti
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    $request->validate([
        'status' => 'required|in:teljesitve,elutasitva',
    ]);

    // Csak akkor módosítható, ha még 'uj' státuszban van
    if ($booking->status === 'uj') {
        $booking->status = $request->status;
        $booking->save();
    }

    return redirect()->back()->with('success', 'Asztalfoglalás státusza frissítve.');
}
}
