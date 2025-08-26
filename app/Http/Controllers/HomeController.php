<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Feedback;

class HomeController extends Controller
{
    /**
     * Főoldal megjelenítése – véletlenszerű ételkártyákkal
     */
    public function welcome()
{
    $randomDishes = [];

    // Csak vendég vagy user szerepkörű felhasználó láthatja a kínálatot
    if (!auth()->check() || auth()->user()->role === 'user') {
        $randomDishes = Dish::where('active', 1)->inRandomOrder()->take(3)->get();
    }

    // Kiemelt értékelések mindenki számára elérhetők
    $highlightedFeedbacks = Feedback::with('user')
        ->where('type', 'rating')
        ->whereIn('rating', [4, 5])
        ->latest()
        ->take(5)
        ->get();

    return view('welcome', compact('randomDishes', 'highlightedFeedbacks'));
}
}
