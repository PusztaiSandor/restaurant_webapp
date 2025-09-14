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
    // Csak bejelentkezés nélkül, vagy 'user' szerepkörű felhasználók érhetik el.

    public function menu(Request $request)
    {

        if (auth()->check() && auth()->user()->role !== 'user') {
            abort(403, 'Az étlap csak user szerepkörű felhasználók számára érhető el.');
        }
        // Alap lekérdezés: csak aktív ételek
        $query = Dish::query()->where('active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

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

        $categories = Dish::select('category')->distinct()->pluck('category');
        $types = Dish::select('type')->distinct()->pluck('type');


        return view('dishes.index', compact('dishes', 'categories', 'types', 'selectedSize'));
    }

    // Egy adott étel részletes megjelenítése.

    public function show($id)
    {
        $dish = Dish::findOrFail($id);

        return view('dishes.show', compact('dish'));
    }

    //Étel létrehozása és szerkesztése

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.create');
    }


    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
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

        // JSON mező átalakítása, egy JSON szöveget alakít át használható tömbbé
        if (! empty($validated['ingredient_modifiers']) && is_string($validated['ingredient_modifiers'])) {
            $json = json_decode($validated['ingredient_modifiers'], true);
            $validated['ingredient_modifiers'] = is_array($json) ? $json : [];
        }

        Dish::create($validated);

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Új étel sikeresen felvéve: „'.$validated['name'].'”');
    }

    // Archivált étel újraaktiválása.

    public function activate(Dish $dish)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $dish->active = true;
        $dish->save();


        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel újra aktiválva: „'.$dish->name.'”');
    }

    // Étel szerkesztése.

    public function edit(Dish $dish)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.edit', compact('dish'));
    }

    public function update(Request $request, Dish $dish)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
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

        $validated['base_ingredients'] = array_map('trim', explode(',', $validated['base_ingredients'] ?? ''));
        $validated['extra_ingredients'] = array_map('trim', explode(',', $validated['extra_ingredients'] ?? ''));
        $validated['allergens'] = array_map('trim', explode(',', $validated['allergens'] ?? ''));


        if (! empty($validated['ingredient_modifiers']) && is_string($validated['ingredient_modifiers'])) {
            $json = json_decode($validated['ingredient_modifiers'], true);
            $validated['ingredient_modifiers'] = is_array($json) ? $json : [];
        }

        $dish->update($validated);


        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel sikeresen frissítve!');
    }

    // Étel archiválása, így az étel eltűnik az étlapról, de nem törlődik.

    public function deactivate(Dish $dish)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $dish->active = false;
        $dish->save();


        return redirect()->route('admin.dishes.index')
            ->with('success', 'Étel archiválva: „'.$dish->name.'”');
    }

    // Készletkezelés

    public function editStock(Dish $dish)
    {

        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.dishes.edit-stock', compact('dish'));
    }


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
            ->with('success', 'Készlet frissítve: „'.$dish->name.'”');
    }
}
