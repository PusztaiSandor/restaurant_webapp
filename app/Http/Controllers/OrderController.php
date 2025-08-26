<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GlobalCharge;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Dish;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'cutlery' => request()->boolean('cutlery_requested'),
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

    // Díjak mentése a session-be a submit() metódus számára
        session()->put('charges', $charges->toArray());

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

    // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'A rendeléshez be kell jelentkezni.');
    }

    // Szállítási mód validálása
    $request->validate([
        'delivery_method' => 'required|in:delivery,pickup,dine-in',
    ]);

    // Felhasználói azonosító lekérése
    $userId = Auth::id();

    // Kosár és extra díjak lekérése a sessionből
    $cart = session('cart', []);
    $charges = session('charges', []);


// Mennyiség validálása minden kosár elemre
foreach ($cart as $item) {
    if (!isset($item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] < 1) {
        return back()->with('error', 'A mennyiség nem lehet nulla vagy negatív.');
    }

    $dish = \App\Models\Dish::find($item['dishes_id']);
    if ($item['quantity'] > $dish->stock) {
        return back()->with('error', 'A rendelni kívánt mennyiség meghaladja a készletet.');
    }
}


    // Ételek összesített ára (bruttó)
    $subtotalSum = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    // Alapértelmezett díjak inicializálása
    $deliveryFee = 0;
    $serviceFee = 0;
    $cutleryFee = 0;
    $discount = 0;

    // Extra díjak feldolgozása a megfelelő szállítási mód alapján
    foreach ($charges as $charge) {
        if ($charge['delivery_method'] !== $request->delivery_method) continue;

        // Százalékos vagy fix összegű díj kiszámítása
        $amount = $charge['is_percentage']
            ? round($subtotalSum * ($charge['value'] / 100), 2)
            : $charge['value'];

        // Díjtípus alapján hozzárendelés
        switch ($charge['charge_type']) {
            case 'delivery_fee':
                $deliveryFee += $amount;
                break;
            case 'service_fee':
                $serviceFee += $amount;
                break;
            case 'cutlery':
                if ($request->has('cutlery_requested')) {
                    $cutleryFee += $amount;
                }
                break;
            case 'order_discount':
                $discount += $amount;
                break;
        }
    }

    // Nettó összeg kiszámítása (ÁFA nélkül)
    $subtotalNet = round($subtotalSum / 1.27, 2); // 27% ÁFA feltételezve
    $totalTax = $subtotalSum - $subtotalNet;

    // Teljes fizetendő összeg kiszámítása
    $totalPrice = $subtotalSum + $deliveryFee + $serviceFee + $cutleryFee - $discount;

    // Új rendelés létrehozása és mentése
    $order = new Order();
    $order->users_id = $userId;
    $order->courier_id = null;
    $order->status = 'uj';
    $order->payment_method = 'bankkartya';
    $order->delivery_method = $request->delivery_method;
    $order->is_paid = false;
    $order->payment_time = null;
    $order->delivery_fee = $deliveryFee;
    $order->service_fee = $serviceFee;
    $order->cutlery_fee = $cutleryFee;
    $order->discount = $discount;
    $order->subtotal_net = $subtotalNet;
    $order->subtotal_sum = $subtotalSum;
    $order->total_tax = $totalTax;
    $order->total_price = $totalPrice;
    $order->rating_star = null;
    $order->rating_comment = null;
    $order->save();

    // Rendeléshez tartozó tételek mentése
    foreach ($cart as $item) {
        $orderItem = new OrderItem();
        $orderItem->orders_id = $order->orders_id;
        $orderItem->dishes_id = $item['dishes_id'];
        $orderItem->size = $item['size'] ?? 'Normál';
        $orderItem->size_multiplier = $item['size_multiplier'] ?? 1.00;
        $orderItem->quantity = $item['quantity'];

        // Extra és kizárt hozzávalók mentése
        $orderItem->extra_ingredients = $item['extra_ingredients'] ?? null;
        $orderItem->excluded_ingredients = $item['excluded_ingredients'] ?? null;

        // Extra hozzávalók ára és kizárások kedvezménye a kosárból
        $orderItem->ingredient_price = round($item['ingredient_price'] ?? 0.00, 2);
        $orderItem->exclusion_discount = round($item['exclusion_discount'] ?? 0.00, 2);

        // Egységár kiszámítása (bruttó)
        $basePrice = $item['price']; // már tartalmazza az extrákat és kizárásokat
        $multiplier = $item['size_multiplier'] ?? 1.00;

        $finalUnitPrice = round($item['price'], 2);

        $orderItem->final_unit_price = $finalUnitPrice;

        // ÁFA lekérése a dishes táblából
        $dish = \App\Models\Dish::find($item['dishes_id']);
        $taxRate = ($dish->tax_percent ?? 27) / 100;
        $orderItem->tax_amount = round($finalUnitPrice * $taxRate, 2);

        // Végösszeg kiszámítása (ÁFA már benne van az egységárban)
        $orderItem->subtotal = round($finalUnitPrice * $item['quantity'], 2);

        $orderItem->save();
    }

    // Kosár ürítése és visszairányítás
    session()->forget('cart');
    return redirect()->route('home')->with('success', 'A rendelés sikeresen elküldve!');
}

public function myOrders()
{
    $userId = Auth::id();
    $orders = Order::with('items.dish')
        ->where('users_id', $userId)
        ->orderByDesc('created_at')
        ->get();

    return view('orders.myorders', compact('orders'));
}



public function cancel($orderId)
{
    // Lekérjük a rendelést, ha nem létezik, hibát dob
    $order = Order::findOrFail($orderId);

    // Jogosultság ellenőrzés – csak saját rendelés törölhető
    if ($order->users_id !== Auth::id()) {
        return back()->with('error', 'Nem jogosult a rendelés törlésére.');
    }

    // Csak akkor törölhető, ha státusz engedélyezett és még nincs fizetve
    if (!in_array($order->status, ['uj', 'keszul', 'atvetelre_kesz']) || $order->is_paid) {
        return back()->with('error', 'Csak fizetetlen, aktív rendelést lehet törölni.');
    }

    // Rendelés státusz módosítása
    $order->status = 'torolve';
    $order->save();

    // Kapcsolódó asztalfoglalás kezelése
    if (
        $order->booking && // van foglalás
        !in_array($order->booking->status, ['teljesitve', 'elutasitva', 'torolve']) // még aktív
    ) {
        $order->booking->status = 'torolve';
        $order->booking->save();
    }

    // Visszajelzés a felhasználónak
    return back()->with('success', 'A rendelés és az esetleges foglalás törölve lett.');
}

public function showPaymentForm(Order $order)
{
    if (!in_array($order->status, ['uj', 'keszul', 'atvetelre_kesz']) || $order->is_paid) {
    return redirect()->route('orders.myorders')->with('error', 'Ez a rendelés már fizetve vagy nem aktív.');
}

    return view('orders.pay', compact('order'));
}

public function simulatePayment(Request $request, Order $order)
{
    $request->validate([
        'payment_method' => 'required|in:bankkartya,keszpenz,szepkartya',
        'card_name' => 'nullable|string|max:255',
        'card_number' => 'nullable|string|max:19',
        'card_expiry' => 'nullable|string|max:5',
        'card_cvc' => 'nullable|string|max:4',
        'cash_given' => 'nullable|integer|min:0',
    ]);

    $total = (int) round($order->total_price);

    if ($request->payment_method === 'keszpenz') {
        $cash = (int) $request->cash_given;

        if ($cash < $total) {
            return back()->with('error', 'Az átadott összeg nem elegendő a fizetéshez.');
        }

        if ($cash % 5 !== 0) {
            return back()->with('error', 'Az átadott összegnek oszthatónak kell lennie 5-tel.');
        }

        $change = $cash - $total;

// Magyarországi 2 Ft-os szabály: kerekítés 5 Ft-ra
$changeRounded = round($change / 5) * 5;

// Ha a kerekítés lefelé történik, és a különbség 1 vagy 2 Ft, akkor felfelé kerekítünk
if ($changeRounded < $change && ($change - $changeRounded) <= 2) {
    $changeRounded += 5;
}

$order->is_paid = true;
$order->payment_method = 'keszpenz';
$order->payment_time = now();
$order->save();

return redirect()->route('orders.myorders')->with('success', "Fizetés sikeres. Visszajáró: {$changeRounded} Ft");
    }

    // Bankkártya vagy SZÉP kártya
    $order->is_paid = true;
    $order->payment_method = $request->payment_method;
    $order->payment_time = now();
    $order->save();

    return redirect()->route('orders.myorders')->with('success', 'Fizetés szimulálása sikeres.');
}

public function rate(Request $request, Order $order)
{
    // Csak saját rendelés értékelhető
    if ($order->users_id !== Auth::id()) {
        return back()->with('error', 'Nem jogosult az értékelésre.');
    }

    // Csak fizetett rendelés értékelhető
    if (!$order->is_paid) {
        return back()->with('error', 'Csak fizetett rendelést lehet értékelni.');
    }

    // Ne lehessen újra értékelni
    if (!is_null($order->rating_star)) {
        return back()->with('error', 'Ez a rendelés már értékelve lett.');
    }

    $request->validate([
    'rating_star' => 'required|integer|min:1|max:5',
    'rating_comment' => 'nullable|string|max:300|regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 .,!?()@\\-\\n\\r]*$/u',
], [
    'rating_star.required' => 'Kérlek válaszd ki az értékelést.',
    'rating_star.integer' => 'Az értékelés csak egész szám lehet.',
    'rating_star.min' => 'Legalább 1 csillagot kell választani.',
    'rating_star.max' => 'Legfeljebb 5 csillagot lehet választani.',
    'rating_comment.max' => 'A megjegyzés legfeljebb 300 karakter lehet.',
    'rating_comment.regex' => 'A megjegyzés csak betűket, számokat és írásjeleket tartalmazhat.',
]);

    $order->rating_star = $request->rating_star;
    $order->rating_comment = $request->rating_comment;
    $order->save();

    return back()->with('success', 'Köszönjük az értékelést!');
}

public function downloadInvoice(Order $order)
{
    // Csak saját, fizetett rendeléshez engedélyezett
    if ($order->users_id !== auth()->id()) {
        abort(403);
    }

    if (!$order->is_paid) {
        return back()->with('error', 'Csak fizetett rendeléshez tölthető le számla.');
    }

    $order->load(['items.dish']);

    $pdf = Pdf::loadView('pdf.invoice', compact('order'))
        ->setOptions(['defaultFont' => 'DejaVu Sans']);

    return $pdf->download('szamla_rendeles_' . $order->orders_id . '.pdf');
}

}
