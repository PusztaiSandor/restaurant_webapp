<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Kosár lekérése sessionből
        $cart = session()->get('cart', []);

        return view('orders.cart', compact('cart'));
    }

    public function quickAdd(Request $request, $dishId)
{
    // 🔍 Étel lekérése adatbázisból
    $dish = Dish::findOrFail($dishId);

    // 📏 Méret lekérése (alapértelmezett: 'Normál')
    $size = $request->input('size', 'Normál');
    $sizeOptions = $dish->size_options ?? [];

    // ⚠️ Ha nincs ilyen méret, használjuk az első elérhetőt
    if (!isset($sizeOptions[$size])) {
        $size = isset($sizeOptions['Normál']) ? 'Normál' : array_key_first($sizeOptions);
    }

    // 🔢 Mennyiség lekérése
    $quantity = max(1, (int) $request->input('quantity', 1));

    // 💰 Ár kiszámítása a kiválasztott méret alapján
    $price = $dish->getDiscountedSizePrice($size);

    // 🛒 Kosár lekérése a session-ből
    $cart = session()->get('cart', []);

    // 🔑 Egyedi kulcs generálása (étel ID + méret)
    $key = $dish->dishes_id . '_' . $size;

    // 📦 Ha már van ilyen tétel, növeljük a mennyiséget
    if (isset($cart[$key])) {
        $cart[$key]['quantity'] += $quantity;
    } else {
        $cart[$key] = [
            'dishes_id' => $dish->dishes_id,
            'name' => $dish->name,
            'size' => $size,
            'quantity' => $quantity,
            'price' => $price,
        ];
    }

    // 💾 Kosár visszamentése a session-be
    session()->put('cart', $cart);

    // ✅ Visszairányítás sikerüzenettel
    return redirect()->route('menu')->with('success', 'A termék sikeresen a kosárba került!');
}

public function increase($key)
{
    $cart = session()->get('cart', []);
    if (isset($cart[$key])) {
        $cart[$key]['quantity'] += 1;
        session()->put('cart', $cart);
    }
    return redirect()->route('cart.index');
}

public function decrease($key)
{
    $cart = session()->get('cart', []);
    if (isset($cart[$key])) {
        $cart[$key]['quantity'] -= 1;
        if ($cart[$key]['quantity'] <= 0) {
            unset($cart[$key]);
        }
        session()->put('cart', $cart);
    }
    return redirect()->route('cart.index');
}

public function remove($key)
{
    $cart = session()->get('cart', []);
    if (isset($cart[$key])) {
        unset($cart[$key]);
        session()->put('cart', $cart);
    }
    return redirect()->route('cart.index');
}

public function clear()
{
    session()->forget('cart');
    return redirect()->route('cart.index')->with('success', 'A kosár sikeresen kiürítve.');
}

public function checkout()
{
    $cart = session()->get('cart', []);
    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('orders.checkout', compact('cart', 'total'));
}

public function add(Request $request, $dishId)
{
    $dish = Dish::findOrFail($dishId);

    // 📏 Méret
    $size = $request->input('size', 'Normál');
    $sizeOptions = $dish->size_options ?? [];
    if (!isset($sizeOptions[$size])) {
        $size = array_key_first($sizeOptions);
    }

    // 🔢 Mennyiség
    $quantity = max(1, (int) $request->input('quantity', 1));

    // ➕ Extrák
    $extraIngredients = $request->input('extra_ingredients', []);
    $excludedIngredients = $request->input('excluded_ingredients', []);
    $modifiers = $dish->ingredient_modifiers ?? [];

    // 💰 Alapár méret alapján
    $basePrice = $dish->getDiscountedSizePrice($size);

    // ➕ Extrák árának összeadása
    $extraTotal = 0;
    foreach ($extraIngredients as $extra) {
        $extraTotal += $modifiers[$extra] ?? 0;
    }

    // ❌ Kizárások árának összeadása
    $excludedTotal = 0;
    foreach ($excludedIngredients as $excluded) {
        $excludedTotal += $modifiers[$excluded] ?? 0;
    }

    // 🧾 Végső ár
    $finalPrice = ($basePrice + $extraTotal + $excludedTotal);

    // 🔑 Egyedi kulcs generálása
    $keyData = [
        'dishes_id' => $dish->dishes_id,
        'size' => $size,
        'extra' => $extraIngredients,
        'excluded' => $excludedIngredients,
    ];
    $key = md5(json_encode($keyData));

    // 🛒 Kosár frissítése
    $cart = session()->get('cart', []);
    if (isset($cart[$key])) {
        $cart[$key]['quantity'] += $quantity;
    } else {
        $cart[$key] = [
           'dishes_id' => $dish->dishes_id,
            'name' => $dish->name,
            'size' => $size,
            'extra_ingredients' => $extraIngredients,
            'excluded_ingredients' => $excludedIngredients,
            'ingredient_price' => round($extraTotal, 2),
            'exclusion_discount' => round($excludedTotal, 2),
            'quantity' => $quantity,
            'price' => $finalPrice,
        ];
    }

    session()->put('cart', $cart);

    return redirect()->route('cart.index')->with('success', 'A termék sikeresen a kosárba került!');
}

}
