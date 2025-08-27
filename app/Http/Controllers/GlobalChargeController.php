<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlobalCharge;

class GlobalChargeController extends Controller
{
    // Globális díjak listázása az admin felületen.
    // A legfrissebb díjak kerülnek előre.
    public function index()
    {
        $charges = GlobalCharge::orderBy('created_at', 'desc')->get();
        return view('admin.global_charges.index', compact('charges'));
    }

    // Új díj létrehozása – az űrlap megjelenítése.
    // Az admin itt tudja megadni a díj típusát, értékét, és egyéb tulajdonságait.
    public function create()
    {
        return view('admin.global_charges.create');
    }

    // Új díj mentése az adatbázisba.
    // Validáljuk az adatokat, majd létrehozzuk a GlobalCharge rekordot.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'charge_type' => 'required|in:delivery_fee,service_fee,order_discount,cutlery',
            'delivery_method' => 'required|in:delivery,dine-in,pickup',
            'value' => 'required|numeric|min:0',
            'is_percentage' => 'required|boolean',
            'is_active' => 'required|boolean',
            'is_optional' => 'required|boolean',
            'description' => 'nullable|string',
        ]);
// Új díj létrehozása
        GlobalCharge::create($validated);

        // Visszairányítás a díjak listájához, sikeres üzenettel
        return redirect()->route('global-charges.index')->with('success', 'Díj sikeresen létrehozva!');
    }

    // Díj szerkesztő űrlap megjelenítése.
    // Az admin itt tudja módosítani a díj adatait.
    public function edit($id)
    {
        $charge = GlobalCharge::findOrFail($id);
        return view('admin.global_charges.edit', compact('charge'));
    }

    // Díj frissítése az adatbázisban.
    // Validáljuk az új adatokat, majd mentjük a módosításokat.
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'charge_type' => 'required|in:delivery_fee,service_fee,order_discount,cutlery',
            'delivery_method' => 'required|in:delivery,dine-in,pickup',
            'value' => 'required|numeric|min:0',
            'is_percentage' => 'required|boolean',
            'is_active' => 'required|boolean',
            'is_optional' => 'required|boolean',
            'description' => 'nullable|string',
        ]);


        $charge = GlobalCharge::findOrFail($id);
        $charge->update($validated);

        return redirect()->route('global-charges.index')->with('success', 'Díj frissítve!');
    }

    // Díj törlése az adatbázisból.
    // Véglegesen eltávolítja a kiválasztott díjat.
    public function destroy($id)
    {
        $charge = GlobalCharge::findOrFail($id);
        $charge->delete();

        return redirect()->route('global-charges.index')->with('success', 'Díj törölve!');
    }
}
