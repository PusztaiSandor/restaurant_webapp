<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use PDF;

class DishController extends Controller
{
    /**
     * Admin felület: Aktív és archivált ételek listázása
     */
    public function index()
    {
        $activeDishes = Dish::where('active', true)->get();
        $archivedDishes = Dish::where('active', false)->get();

        return view('admin.dishes.index', compact('activeDishes', 'archivedDishes'));
    }

    /**
     * Publikus étlap megjelenítése szűrőkkel
     */
    public function menu(Request $request)
{

if (auth()->check() && auth()->user()->role !== 'user') {
        abort(403, 'Az étlap csak user szerepkörű felhasználók számára érhető el.');
    }

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

    // Ételek lekérése
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

    // Szűrőhöz szükséges értékek
    $categories = Dish::select('category')->distinct()->pluck('category');
    $types = Dish::select('type')->distinct()->pluck('type');

    // Nézet visszaadása, a kiválasztott méretet is átadjuk
    return view('dishes.index', compact('dishes', 'categories', 'types', 'selectedSize'));
}

public function show($id)
{
    $dish = Dish::findOrFail($id);
    return view('dishes.show', compact('dish'));
}

    /**
     * Új étel létrehozásának űrlapja (csak admin)
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403); // 🔒 Jogosultság ellenőrzés
        }

        return view('admin.dishes.create');
    }

    /**
     * Új étel mentése adatbázisba (csak admin)
     */
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
            if (!empty($label)) {
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

        // JSON mező dekódolása
        if (!empty($validated['ingredient_modifiers']) && is_string($validated['ingredient_modifiers'])) {
            $json = json_decode($validated['ingredient_modifiers'], true);
            $validated['ingredient_modifiers'] = is_array($json) ? $json : [];
        }

        Dish::create($validated);

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Új étel sikeresen felvéve: „' . $validated['name'] . '”');
    }
    /**
     * Archivált étel újraaktiválása (csak admin)
     */
    public function activate(Dish $dish)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $dish->active = true;
        $dish->save();

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel újra aktiválva: „' . $dish->name . '”');
    }

    /**
     * Étel szerkesztő űrlap megjelenítése (csak admin)
     */
    public function edit(Dish $dish)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.edit', compact('dish'));
    }

    /**
     * Étel frissítése adatbázisban (csak admin)
     */
    public function update(Request $request, Dish $dish)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Validáció
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

        // Méretprofil újraépítése
        $labels = $request->input('size_labels', []);
        $units = $request->input('size_units', []);
        $amounts = $request->input('size_amounts', []);
        $multipliers = $request->input('size_multipliers', []);
        $modifiers = $request->input('size_modifiers', []);

        $sizeOptions = [];

        foreach ($labels as $index => $label) {
            if (!empty($label)) {
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

        // JSON mező dekódolása
        if (!empty($validated['ingredient_modifiers']) && is_string($validated['ingredient_modifiers'])) {
            $json = json_decode($validated['ingredient_modifiers'], true);
            $validated['ingredient_modifiers'] = is_array($json) ? $json : [];
        }

        $dish->update($validated);

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel sikeresen frissítve!');
    }
    /**
     * Étel archiválása (csak admin)
     */
    public function deactivate(Dish $dish)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $dish->active = false;
        $dish->save();

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel archiválva: „' . $dish->name . '”');
    }

    /**
     * Készlet módosító űrlap megjelenítése (csak admin)
     */
    public function editStock(Dish $dish)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.edit-stock', compact('dish'));
    }

    /**
     * Készlet módosítás mentése (csak admin)
     */
    public function updateStock(Request $request, Dish $dish)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $dish->stock = $validated['stock'];
        $dish->save();

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Készlet frissítve: „' . $dish->name . '”');
    }

}
