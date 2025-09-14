<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    // Asztalok listázása az admin felületen.

    public function index()
    {
        $tables = Table::orderBy('tables_id')->get();

        return view('admin.tables.index', compact('tables'));
    }

    // Új asztal létrehozása.

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'location' => 'required|in:beltér,terasz',
            'position' => 'required|in:bal,közép,jobb',
            'capacity' => 'required|integer|min:1',
            'is_reservable' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

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
        $code = substr($table->location, 0, 1).substr($table->position, 0, 1).$table->tables_id;
        $table->table_code = strtolower($code);
        $table->save();

        return redirect()->route('admin.tables.index')->with('success', 'Asztal sikeresen létrehozva.');
    }

    // Asztal szerkesztése.

    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }


    public function update(Request $request, Table $table)
    {
        $request->validate([
            'location' => 'required|in:beltér,terasz',
            'position' => 'required|in:bal,közép,jobb',
            'capacity' => 'required|integer|min:1',
            'is_reservable' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $table->location = $request->location;
        $table->position = $request->position;
        $table->capacity = $request->capacity;
        $table->is_reservable = $request->is_reservable;
        $table->notes = $request->notes;

        // Frissített table_code generálás.
        
        $code = substr($table->location, 0, 1).substr($table->position, 0, 1).$table->tables_id;
        $table->table_code = strtolower($code);
        $table->save();

        return redirect()->route('admin.tables.index')->with('success', 'Asztal sikeresen frissítve.');
    }
}
