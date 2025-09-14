<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Feedback;

class HomeController extends Controller
{
    // Főoldal megjelenítése, véletlenszerű ételkártyákkal és kiemelt értékelésekkel.
    // A kínálat csak vendégeknek és 'user' szerepkörű felhasználóknak jelenik meg.

    public function welcome()
    {
        $randomDishes = [];

        if (! auth()->check() || auth()->user()->role === 'user') {
            $randomDishes = Dish::where('active', 1)->inRandomOrder()->take(3)->get();
        }

        // Kiemelt értékelések lekérése:
        // Mindenki számára elérhetők, függetlenül a szerepkörtől.
        // Csak 4 vagy 5 csillagos értékeléseket jelenítünk meg.

        $highlightedFeedbacks = Feedback::with('user')
            ->where('type', 'rating')
            ->whereIn('rating', [4, 5])
            ->latest()
            ->take(4)
            ->get();

        return view('welcome', compact('randomDishes', 'highlightedFeedbacks'));
    }
}
