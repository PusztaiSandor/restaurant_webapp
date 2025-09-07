<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    // Asztalok listázása az admin felületen.
    // Az adatbázisból lekérdezzük az összes asztalt, és átadjuk a nézetnek.
    public function index()
    {
        $tables = Table::orderBy('tables_id')->get();

        return view('admin.tables.index', compact('tables'));
    }

    // Új asztal létrehozása.
    // Csak a nézetet jelenítjük meg, ahol az admin megadhatja az új asztal adatait.
    public function create()
    {
        return view('admin.tables.create');
    }

    // Asztal mentése az adatbázisba.
    // Először validáljuk a beküldött adatokat, majd létrehozzuk az új asztalt.
    public function store(Request $request)
    {

        // Beküldött adatok ellenőrzése.
        $request->validate([
            'location' => 'required|in:beltér,terasz',
            'position' => 'required|in:bal,közép,jobb',
            'capacity' => 'required|integer|min:1',
            'is_reservable' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        // Új asztal példány létrehozása és mezők kitöltése a beküldött adatok alapján.

        $table = new Table;
        $table->location = $request->location;
        $table->position = $request->position;
        $table->capacity = $request->capacity;
        $table->is_reservable = $request->is_reservable;
        $table->notes = $request->notes;

        // Ideiglenes table_code beállítása, hogy az első mentés ne dobjon hibát.
        $table->table_code = 'temp';
        $table->save();

        // Automatikus table_code generálás.
        // A kód az elhelyezés és pozíció kezdőbetűjéből + az adatbázisban generált ID-ből áll.
        $code = substr($table->location, 0, 1).substr($table->position, 0, 1).$table->tables_id;
        $table->table_code = strtolower($code);
        $table->save();

        // Visszairányítás az asztallista oldalra, sikeres mentés üzenettel.
        return redirect()->route('admin.tables.index')->with('success', 'Asztal sikeresen létrehozva.');
    }

    // Asztal szerkesztése.
    // Betöltjük az adott asztal adatait, és átadjuk a szerkesztő nézetnek.
    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    // Asztal frissítése.
    // Validáljuk a beküldött adatokat, majd frissítjük az asztal adatait.
    public function update(Request $request, Table $table)
    {
        // Beküldött adatok ellenőrzése.
        $request->validate([
            'location' => 'required|in:beltér,terasz',
            'position' => 'required|in:bal,közép,jobb',
            'capacity' => 'required|integer|min:1',
            'is_reservable' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        // Asztal mezőinek frissítése.

        $table->location = $request->location;
        $table->position = $request->position;
        $table->capacity = $request->capacity;
        $table->is_reservable = $request->is_reservable;
        $table->notes = $request->notes;

        // Frissített table_code generálás.
        $code = substr($table->location, 0, 1).substr($table->position, 0, 1).$table->tables_id;
        $table->table_code = strtolower($code);
        $table->save();

        // Visszairányítás az asztallista oldalra, sikeres frissítés üzenettel.
        return redirect()->route('admin.tables.index')->with('success', 'Asztal sikeresen frissítve.');
    }
}
