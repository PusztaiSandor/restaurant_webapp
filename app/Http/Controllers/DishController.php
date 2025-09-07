<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    // Admin felület: Aktív és archivált ételek listázása

    public function index()
    {
        $activeDishes = Dish::where('active', true)->get();
        $archivedDishes = Dish::where('active', false)->get();

        return view('admin.dishes.index', compact('activeDishes', 'archivedDishes'));
    }

    // Publikus étlap megjelenítése szűrőkkel.
    // Csak 'user' szerepkörű felhasználók érhetik el.
    // Lehetőség van kategória, típus és ár szerinti szűrésre.

    public function menu(Request $request)
    {

        // Jogosultság ellenőrzése: csak 'user' szerepkörű felhasználók
        if (auth()->check() && auth()->user()->role !== 'user') {
            abort(403, 'Az étlap csak user szerepkörű felhasználók számára érhető el.');
        }
        // Alap lekérdezés: csak aktív ételek
        $query = Dish::query()->where('active', true);

        // Szűrés kategória szerint
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Szűrés típus szerint
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Kiválasztott méret lekérése (alapértelmezett: 'Normál')
        $selectedSize = $request->input('size', 'Normál');

        // Ételek lekérése az adatbázisból
        $dishes = $query->get();

        // Ár szerinti rendezés (mindig a „Normál” méret alapján)
        if ($request->sort === 'price_asc') {
            $dishes = $dishes->sortBy(function ($dish) {
                return $dish->getDiscountedSizePrice('Normál');
            })->values();
        } elseif ($request->sort === 'price_desc') {
            $dishes = $dishes->sortByDesc(function ($dish) {
                return $dish->getDiscountedSizePrice('Normál');
            })->values();
        }

        // Szűrőhöz szükséges értékek lekérése
        $categories = Dish::select('category')->distinct()->pluck('category');
        $types = Dish::select('type')->distinct()->pluck('type');

        // Nézet visszaadása, a kiválasztott méretet is átadjuk
        return view('dishes.index', compact('dishes', 'categories', 'types', 'selectedSize'));
    }

    // Egy adott étel részletes megjelenítése.
    // Az id alapján lekérjük az ételt, és átadjuk a nézetnek.
    public function show($id)
    {
        $dish = Dish::findOrFail($id);

        return view('dishes.show', compact('dish'));
    }

    // Új étel létrehozásának űrlapja (csak admin)

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403); // 🔒 Jogosultság ellenőrzés
        }

        return view('admin.dishes.create');
    }

    // Új étel mentése adatbázisba (csak admin)

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Validáció a mezők alapján
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50',
            'gross_price' => 'required|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'on_sale' => 'required|boolean',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'base_ingredients' => 'nullable|string',
            'extra_ingredients' => 'nullable|string',
            'ingredient_modifiers' => 'nullable|string',
            'allergens' => 'nullable|string',
            'calories' => 'nullable|integer|min:0',
            'vegetarian' => 'required|boolean',
            'active' => 'required|boolean',
        ]);

        // Méretprofil összeállítása tömbökből
        $labels = $request->input('size_labels', []);
        $units = $request->input('size_units', []);
        $amounts = $request->input('size_amounts', []);
        $multipliers = $request->input('size_multipliers', []);
        $modifiers = $request->input('size_modifiers', []);

        $sizeOptions = [];

        foreach ($labels as $index => $label) {
            if (! empty($label)) {
                $sizeOptions[$label] = [
                    'unit' => $units[$index] ?? '',
                    'amount' => floatval($amounts[$index] ?? 0),
                    'multiplier' => floatval($multipliers[$index] ?? 1.0),
                ];
            }
        }

        $validated['size_options'] = $sizeOptions;

        // Tömb típusú mezők konvertálása
        $validated['base_ingredients'] = array_map('trim', explode(',', $validated['base_ingredients'] ?? ''));
        $validated['extra_ingredients'] = array_map('trim', explode(',', $validated['extra_ingredients'] ?? ''));
        $validated['allergens'] = array_map('trim', explode(',', $validated['allergens'] ?? ''));

        // JSON mező dekódolása egy JSON szöveget alakít át használható tömbbé
        if (! empty($validated['ingredient_modifiers']) && is_string($validated['ingredient_modifiers'])) {
            $json = json_decode($validated['ingredient_modifiers'], true);
            $validated['ingredient_modifiers'] = is_array($json) ? $json : [];
        }

        // Étel mentése az adatbázisba
        Dish::create($validated);

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Új étel sikeresen felvéve: „'.$validated['name'].'”');
    }

    // Archivált étel újraaktiválása (csak admin).
    // Az 'active' mezőt igazra állítjuk, így az étel újra megjelenik az étlapon.

    public function activate(Dish $dish)
    {
        // Jogosultság ellenőrzése: csak admin végezheti
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        // Étel újraaktiválása
        $dish->active = true;
        $dish->save();

        // Visszairányítás az admin étellistához, sikeres üzenettel
        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel újra aktiválva: „'.$dish->name.'”');
    }

    // Étel szerkesztő űrlap megjelenítése (csak admin).
    // Az adott étel adatait betöltjük, és átadjuk a szerkesztő nézetnek.

    public function edit(Dish $dish)
    {
        // Jogosultság ellenőrzése: csak admin végezheti
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.edit', compact('dish'));
    }

    // Étel frissítése adatbázisban (csak admin).
    // Validáljuk az adatokat, újraépítjük a méretprofilokat és összetevőket, majd mentjük.

    public function update(Request $request, Dish $dish)
    {
        // Jogosultság ellenőrzése: csak admin végezheti
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Beküldött adatok validálása
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50',
            'gross_price' => 'required|numeric|min:0',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'on_sale' => 'required|boolean',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'base_ingredients' => 'nullable|string',
            'extra_ingredients' => 'nullable|string',
            'ingredient_modifiers' => 'nullable|string',
            'allergens' => 'nullable|string',
            'calories' => 'nullable|integer|min:0',
            'vegetarian' => 'required|boolean',
            'active' => 'required|boolean',
        ]);

        // Méretprofil újraépítése az űrlap mezőkből
        $labels = $request->input('size_labels', []);
        $units = $request->input('size_units', []);
        $amounts = $request->input('size_amounts', []);
        $multipliers = $request->input('size_multipliers', []);
        $modifiers = $request->input('size_modifiers', []);

        $sizeOptions = [];
        // Minden mérethez tartozik egy címke, mennyiség, mértékegység és szorzó
        foreach ($labels as $index => $label) {
            if (! empty($label)) {
                $sizeOptions[$label] = [
                    'unit' => $units[$index] ?? '',
                    'amount' => floatval($amounts[$index] ?? 0),
                    'multiplier' => floatval($multipliers[$index] ?? 1.0),
                ];
            }
        }

        $validated['size_options'] = $sizeOptions;

        // Tömb típusú mezők konvertálása (pl. összetevők, allergének)
        $validated['base_ingredients'] = array_map('trim', explode(',', $validated['base_ingredients'] ?? ''));
        $validated['extra_ingredients'] = array_map('trim', explode(',', $validated['extra_ingredients'] ?? ''));
        $validated['allergens'] = array_map('trim', explode(',', $validated['allergens'] ?? ''));

        // JSON mező dekódolása (pl. összetevő módosítók)
        // Ha az admin JSON szöveget adott meg, azt tömbbé alakítjuk
        if (! empty($validated['ingredient_modifiers']) && is_string($validated['ingredient_modifiers'])) {
            $json = json_decode($validated['ingredient_modifiers'], true);
            $validated['ingredient_modifiers'] = is_array($json) ? $json : [];
        }
        // Étel frissítése az adatbázisban
        $dish->update($validated);

        // Visszairányítás az admin étellistához, sikeres üzenettel
        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel sikeresen frissítve!');
    }

    // Étel archiválása (csak admin).
    // Az 'active' mezőt hamisra állítjuk, így az étel eltűnik az étlapról, de nem törlődik.

    public function deactivate(Dish $dish)
    {
        // Jogosultság ellenőrzése: csak admin végezheti
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        // Étel inaktiválása (archiválás)
        $dish->active = false;
        $dish->save();

        // Visszairányítás az admin étellistához, sikeres üzenettel
        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel archiválva: „'.$dish->name.'”');
    }

    // Készlet módosító űrlap megjelenítése (csak admin).
    // Az admin itt tudja megadni, hogy hány darab érhető el az adott ételből.

    public function editStock(Dish $dish)
    {
        // Jogosultság ellenőrzése: csak admin végezheti
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.edit-stock', compact('dish'));
    }

    // Nézet megjelenítése, ahol az admin módosíthatja a készletet

    public function updateStock(Request $request, Dish $dish)
    {
        // Jogosultság ellenőrzése: csak admin végezheti
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        // Beküldött készletérték validálása: egész szám, legalább 0
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        // Új készlet mentése az ételhez
        $dish->stock = $validated['stock'];
        $dish->save();

        // Visszairányítás az admin étellistához, sikeres üzenettel
        return redirect()->route('admin.dishes.index')
            ->with('success', 'Készlet frissítve: „'.$dish->name.'”');
    }
}
