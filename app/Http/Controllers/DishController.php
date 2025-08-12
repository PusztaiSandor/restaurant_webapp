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
        $query = Dish::query()->where('active', true);

        // ➤ Szűrés kategória szerint
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ➤ Szűrés típus szerint
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // ➤ Ár szerinti rendezés (bruttó, kedvezményes ár alapján)


        $dishes = $query->get();

        if ($request->sort === 'price_asc') {
        $dishes = $dishes->sortBy(function ($dish) {
        return $dish->getFinalPrice();
        })->values();
        } elseif ($request->sort === 'price_desc') {
        $dishes = $dishes->sortByDesc(function ($dish) {
        return $dish->getFinalPrice();
        })->values();
        }

        // ➤ Szűrőhöz szükséges értékek
        $categories = Dish::select('category')->distinct()->pluck('category');
        $types = Dish::select('type')->distinct()->pluck('type');

        return view('dishes.index', compact('dishes', 'categories', 'types'));
    }

    /**
     * PDF generálása az étlapból
     */
    // public function generateMenuPdf()
    // {
    //     $dishes = Dish::where('active', true)->get();

    //     foreach ($dishes as $dish) {
    //         $taxRate = ($dish->tax_percent ?? 27) / 100;

    //         $sizeOptions = $dish->size_options ?? [];
    //         $defaultSize = isset($sizeOptions['Normál']) ? 'Normál' : array_key_first($sizeOptions);
    //         $defaultSizeData = $sizeOptions[$defaultSize] ?? ['multiplier' => 1.0, 'price_modifier' => 0];

    //         $baseNet = $dish->base_price ?? 0;
    //         $sizeMultiplier = $defaultSizeData['multiplier'] ?? 1.0;
    //         $sizeModifier = $defaultSizeData['price_modifier'] ?? 0;
    //         $dynamicMultiplier = $dish->dynamic_multiplier ?? 1;

    //         $grossBase = ($baseNet * $dynamicMultiplier * $sizeMultiplier + $sizeModifier) * (1 + $taxRate);
    //         $discountPercent = $dish->on_sale ? ($dish->discount_percent ?? 0) : 0;
    //         $finalGross = $grossBase * (1 - $discountPercent / 100);

    //         // ➤ Árak hozzáadása a modellhez
    //         $dish->price_gross = round($finalGross);
    //         $dish->price_original = round($grossBase);
    //         $dish->price_discount_percent = $discountPercent;
    //         $dish->price_size_label = $defaultSize;
    //     }

    //     $categorized = $dishes->filter(fn($dish) => !empty($dish->category) && !empty($dish->type));
    //     $uncategorized = $dishes->filter(fn($dish) => empty($dish->category) || empty($dish->type));

    //     return Pdf::loadView('pdf.menu', [
    //         'categorized' => $categorized,
    //         'uncategorized' => $uncategorized,
    //     ])->download('etlap.pdf');
    // }
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

        // ✅ Validáció a mezők alapján
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

        // ✅ Méretprofil összeállítása tömbökből
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

        // ✅ Tömb típusú mezők konvertálása
        $validated['base_ingredients'] = array_map('trim', explode(',', $validated['base_ingredients'] ?? ''));
        $validated['extra_ingredients'] = array_map('trim', explode(',', $validated['extra_ingredients'] ?? ''));
        $validated['allergens'] = array_map('trim', explode(',', $validated['allergens'] ?? ''));

        // ✅ JSON mező dekódolása
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

        // ✅ Validáció
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

        // ✅ Méretprofil újraépítése
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

        // ✅ Tömb típusú mezők konvertálása
        $validated['base_ingredients'] = array_map('trim', explode(',', $validated['base_ingredients'] ?? ''));
        $validated['extra_ingredients'] = array_map('trim', explode(',', $validated['extra_ingredients'] ?? ''));
        $validated['allergens'] = array_map('trim', explode(',', $validated['allergens'] ?? ''));

        // ✅ JSON mező dekódolása
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

    /**
     * Publikus nézet egy adott ételhez
     */
    // public function show(Dish $dish)
    // {
    //     $selectedSize = null;
    //     $price = $dish->base_price;

    //     // ➤ Méretprofil alapján árképzés
    //     if (!empty($dish->size_options)) {
    //         $sizeKeys = array_keys($dish->size_options);
    //         $selectedSize = $sizeKeys[0];
    //         $data = $dish->size_options[$selectedSize] ?? null;

    //         if ($data) {
    //             $multiplier = $data['multiplier'] ?? 1.0;
    //             $modifier = $data['price_modifier'] ?? 0;
    //             $price = ($dish->base_price * $multiplier) + $modifier;
    //         }
    //     }

    //     $tax = $price * ($dish->tax_percent ?? 0) / 100;
    //     $priceWithTax = round($price + $tax, 2);

    //     // ➤ JSON→tömb konverziók a nézethez
    //     $dish->ingredient_modifiers = is_string($dish->ingredient_modifiers)
    //         ? json_decode($dish->ingredient_modifiers, true)
    //         : $dish->ingredient_modifiers;

    //     $dish->extra_ingredients = is_string($dish->extra_ingredients)
    //         ? array_map('trim', explode(',', $dish->extra_ingredients))
    //         : $dish->extra_ingredients;

    //     $dish->base_ingredients = is_string($dish->base_ingredients)
    //         ? array_map('trim', explode(',', $dish->base_ingredients))
    //         : $dish->base_ingredients;

    //     $dish->allergens = is_string($dish->allergens)
    //         ? array_map('trim', explode(',', $dish->allergens))
    //         : $dish->allergens;

    //     return view('dishes.show', compact('dish', 'priceWithTax', 'selectedSize'));
    // }
}
