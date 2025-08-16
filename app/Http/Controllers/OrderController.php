<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;

class OrderController extends Controller
{
    public function add(Request $request, $dishId)
    {
        // Étel lekérése
        $dish = Dish::findOrFail($dishId);

        // Alap adatok
        $quantity = max(1, (int) $request->input('quantity', 1));
        $size = $request->input('size');
        $extraIngredients = $request->input('extra_ingredients', []);
        $excludedIngredients = $request->input('excluded_ingredients', []);

        // Méret szorzó
        $sizeOptions = json_decode($dish->size_options, true);
        $multiplier = isset($sizeOptions[$size]) ? ($sizeOptions[$size]['multiplier'] ?? 1.0) : 1.0;

        // Adó és kedvezmény
        $taxRate = ($dish->tax_percent ?? 27) / 100;
        $discountPercent = ($dish->on_sale && $dish->discount_percent > 0) ? $dish->discount_percent : 0;

        // Alap ár számítása
        $baseNet = $dish->gross_price * $multiplier;
        $discount = $baseNet * ($discountPercent / 100);
        $netAfterDiscount = $baseNet - $discount;
        $grossDishPrice = $netAfterDiscount * (1 + $taxRate);

        // Extra és kizárt hozzávalók
        $modifiers = $dish->ingredient_modifiers;
        $extrasTotal = collect($extraIngredients)->sum(function ($extra) use ($modifiers, $taxRate) {
            $mod = $modifiers[$extra] ?? 0;
            return round($mod * (1 + $taxRate));
        });

        $exclusionsTotal = collect($excludedIngredients)->sum(function ($excluded) use ($modifiers, $taxRate) {
            $mod = $modifiers[$excluded] ?? 0;
            return round($mod * (1 + $taxRate));
        });

        // Teljes ár
        $totalPrice = ($grossDishPrice + $extrasTotal + $exclusionsTotal) * $quantity;

        // Válasz (később: mentés, redirect, kosárba rakás stb.)
        return back()->with('success', 'Rendelés sikeresen kalkulálva: ' . number_format($totalPrice, 0, '', ' ') . ' Ft');
    }
}
