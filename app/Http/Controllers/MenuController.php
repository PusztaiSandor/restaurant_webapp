<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;
use Barryvdh\DomPDF\Facade\Pdf;

class MenuController extends Controller
{

    // PDF letöltése az étlapról.
    // Az aktív ételeket lekérjük, kategória és típus szerint csoportosítjuk,
    // majd egy előre definiált nézet alapján PDF-et generálunk és letöltünk.

    public function downloadPdf()
    {
// Aktív ételek lekérése az adatbázisból
        $dishes = Dish::where('active', true)->get()->groupBy([
            'category',
            function ($dish) {
                return $dish->type;
            }
        ]);

        // PDF generálása a 'pdf.menu' nézet alapján
        // A nézet megkapja a csoportosított ételeket, étterem nevét, szlogent és láblécet
        $pdf = Pdf::loadView('pdf.menu', [
            'dishesGrouped' => $dishes,
            'restaurantName' => 'Esszencia Étterem',
            'slogan' => 'Az ízek és az oktatás esszenciája',
            'footer' => '© 2025 Esszencia Étterem. Minden jog fenntartva. Budapest, Magyarország | +36 1 234 5678 | info@esszencia.hu'
        ]);

        // PDF fájl letöltése a felhasználó gépére
        return $pdf->download('etlap.pdf');
    }
}
