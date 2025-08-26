<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use Barryvdh\DomPDF\Facade\Pdf;

class MenuController extends Controller
{
    /**
     * PDF letöltése az étlapról.
     */
    public function downloadPdf()
    {
        // Aktív ételek lekérése és csoportosítása kategória + típus szerint
        $dishes = Dish::where('active', true)->get()->groupBy([
            'category',
            function ($dish) {
                return $dish->type;
            }
        ]);

        // PDF generálása a nézet alapján
        $pdf = Pdf::loadView('pdf.menu', [
            'dishesGrouped' => $dishes,
            'restaurantName' => 'Esszencia Étterem',
            'slogan' => 'Az ízek és az oktatás esszenciája',
            'footer' => '© 2025 Esszencia Étterem. Minden jog fenntartva. Budapest, Magyarország | +36 1 234 5678 | info@esszencia.hu'
        ]);

        // Letöltés
        return $pdf->download('etlap.pdf');
    }
}
