<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlobalCharge;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Dish;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Rendelés véglegesítése – űrlap megjelenítése
     */
    public function checkout()
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'A kosár üres.');
    }

    // Kosár összegzés
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    // Átvételi mód
    $deliveryMethod = request()->input('delivery_method', 'delivery');

    // Globális díjak
    $charges = GlobalCharge::where('is_active', 1)->get();

    // Díjak alkalmazása
    $totalWithCharges = $subtotal;

    foreach ($charges as $charge) {
        $apply = match ($charge->charge_type) {
            'delivery_fee' => $deliveryMethod === 'delivery',
            'service_fee' => $deliveryMethod === 'dine-in',
            'order_discount' => true,
            'cutlery_fee' => request()->boolean('cutlery_requested'),
            default => false,
        };

        if ($apply) {
            $amount = $charge->is_percentage
                ? round($subtotal * ($charge->value / 100), 2)
                : $charge->value;

            if ($charge->charge_type === 'order_discount') {
                $totalWithCharges -= $amount;
            } else {
                $totalWithCharges += $amount;
            }
        }
    }

    $totalWithCharges = max(0, $totalWithCharges);

    return view('orders.checkout', [
        'cart' => $cart,
        'charges' => $charges,
        'subtotal' => $subtotal,
        'totalWithCharges' => $totalWithCharges,
    ]);
}

    /**
     * Rendelés mentése
     */
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'delivery_method' => 'required|in:delivery,dine-in,pickup',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'A kosár üres.');
        }

        // Rendelés létrehozása
        $order = new Order();
        $order->customer_name = $request->name;
        $order->phone = $request->phone;
        $order->delivery_method = $request->delivery_method;
        $order->status = 'pending';
        $order->created_at = Carbon::now();
        $order->save();

        // Tételek mentése
        foreach ($cart as $item) {
            $orderItem = new OrderItem();
            $orderItem->orders_id = $order->orders_id;
            $orderItem->dishes_id = $item['dish_id'];
            $orderItem->size = $item['size'];
            $orderItem->size_multiplier = 1.00; // opcionálisan számítható
            $orderItem->quantity = $item['quantity'];
            $orderItem->extra_ingredients = json_encode($item['extra_ingredients']);
            $orderItem->excluded_ingredients = json_encode($item['excluded_ingredients']);
            $orderItem->ingredient_price = 0.00; // opcionálisan számítható
            $orderItem->exclusion_discount = 0.00;
            $orderItem->final_unit_price = $item['price'];
            $orderItem->tax_amount = 0.00;
            $orderItem->subtotal = $item['price'] * $item['quantity'];
            $orderItem->created_at = Carbon::now();
            $orderItem->save();
        }

        // Kosár ürítése
        session()->forget('cart');

        return redirect()->route('menu')->with('success', 'A rendelés sikeresen elküldve!');
    }
}
