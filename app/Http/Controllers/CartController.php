<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Kosár tartalmának megjelenítése.
        // A session-ből lekérjük a 'cart' nevű tömböt, ha nincs, akkor üres tömböt adunk vissza.
        $cart = session()->get('cart', []);

        return view('orders.cart', compact('cart'));
    }

    // Gyors hozzáadás a kosárhoz.

    public function quickAdd(Request $request, $dishId)
    {

        $dish = Dish::findOrFail($dishId);

        $size = $request->input('size', 'Normál');
        $sizeOptions = $dish->size_options ?? [];

        if (! isset($sizeOptions[$size])) {
            $size = isset($sizeOptions['Normál']) ? 'Normál' : array_key_first($sizeOptions);
        }

        $quantity = max(1, (int) $request->input('quantity', 1));

        // Ár kiszámítása a kiválasztott méret alapján
        $price = $dish->getDiscountedSizePrice($size);

        // Kosár lekérése a session-ből
        $cart = session()->get('cart', []);

        // Egyedi kulcs generálása (étel id + méret)
        $key = $dish->dishes_id.'_'.$size;

        // Ha már van ilyen tétel, növeljük a mennyiséget
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {

            // Ha még nincs ilyen tétel, újként adjuk hozzá.
            $cart[$key] = [
                'dishes_id' => $dish->dishes_id,
                'name' => $dish->name,
                'size' => $size,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        // Kosár visszamentése a session-be
        session()->put('cart', $cart);

        return redirect()->route('menu')->with('success', 'A termék sikeresen a kosárba került!');
    }
    // Mennyiség növelése egy adott kosár tételnél.
    // A kulcs alapján megtaláljuk a tételt, és növeljük a darabszámot.

    public function increase($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += 1;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    // Mennyiség csökkentése egy adott kosár tételnél.
    // Ha a mennyiség 0 vagy kevesebb lesz, akkor eltávolítjuk a tételt.
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

    // Tétel eltávolítása a kosárból.
    // A megadott kulcs alapján töröljük a tételt.
    public function remove($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    // Teljes kosár kiürítése.
    // A 'cart' nevű session kulcsot töröljük.
    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'A kosár sikeresen kiürítve.');
    }

    // Kosár véglegesítése, megrendelés előtti összesítés.

    public function checkout()
    {
        // Kosár lekérése a session-ből. Ha nincs, üres tömböt adunk vissza.
        $cart = session()->get('cart', []);
        $total = 0;

        // Végösszeg kiszámítása: minden tétel ára * mennyiség

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Átadjuk a kosár és végösszeg adatokat a nézetnek
        return view('orders.checkout', compact('cart', 'total'));
    }

    // Termék hozzáadása a kosárhoz, részletes beállításokkal.
    // Méret, extrák, kizárások, mennyiség és ár alapján történik a hozzáadás.

    public function add(Request $request, $dishId)
    {
        // Étel lekérése az adatbázisból az id alapján
        $dish = Dish::findOrFail($dishId);

        // Méret lekérése az űrlapból. Alapértelmezett: 'Normál'
        $size = $request->input('size', 'Normál');
        $sizeOptions = $dish->size_options ?? [];

        // Ha a megadott méret nem létezik, az első elérhető méretet használjuk
        if (! isset($sizeOptions[$size])) {
            $size = array_key_first($sizeOptions);
        }
        // Méret szorzó lekérése
        $sizeMultiplier = $sizeOptions[$size]['multiplier'] ?? 1.00;

        // Mennyiség lekérése és minimum érték biztosítása
        $quantity = max(1, (int) $request->input('quantity', 1));

        // Nem lehet nulla vagy negatív
        if ($quantity < 1) {
            return back()->with('error', 'A mennyiség nem lehet nulla vagy negatív.');
        }

        // Nem lehet több, mint a készletet
        if ($quantity > $dish->stock) {
            return back()->with('error', 'A rendelni kívánt mennyiség meghaladja a készletet.');
        }

        // Extrák és kizárások lekérése az űrlapból
        $extraIngredients = $request->input('extra_ingredients', []);
        $excludedIngredients = $request->input('excluded_ingredients', []);
        $modifiers = $dish->ingredient_modifiers ?? [];

        // Alapár méret alapján
        $basePrice = $dish->getDiscountedSizePrice($size);

        // Extrák árának összeadása
        $extraTotal = 0;
        foreach ($extraIngredients as $extra) {
            $extraTotal += $modifiers[$extra] ?? 0;
        }

        // Kizárások árának összeadása
        $excludedTotal = 0;
        foreach ($excludedIngredients as $excluded) {
            $excludedTotal += $modifiers[$excluded] ?? 0;
        }

        // Végső ár kiszámítása: alapár + extrák + kizárások
        $finalPrice = ($basePrice + $extraTotal + $excludedTotal);

        // Egyedi kulcs generálása a tételhez (étel ID + méret + extrák + kizárások)

        $keyData = [
            'dishes_id' => $dish->dishes_id,
            'size' => $size,
            'extra' => $extraIngredients,
            'excluded' => $excludedIngredients,
        ];
        $key = md5(json_encode($keyData));

        // Kosár lekérése a session-ből
        $cart = session()->get('cart', []);

        // Ha már van ilyen tétel, növeljük a mennyiséget
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {

            // Új tétel hozzáadása a kosárhoz
            $cart[$key] = [
                'dishes_id' => $dish->dishes_id,
                'name' => $dish->name,
                'size' => $size,
                'size_multiplier' => $sizeMultiplier,
                'extra_ingredients' => $extraIngredients,
                'excluded_ingredients' => $excludedIngredients,
                'ingredient_price' => round($extraTotal, 2),
                'exclusion_discount' => round($excludedTotal, 2),
                'quantity' => $quantity,
                'price' => $finalPrice,
            ];
        }
        // Kosár visszamentése a session-be
        session()->put('cart', $cart);

        return redirect()->route('menu')->with('success', 'A termék sikeresen a kosárba került!');
    }
}
