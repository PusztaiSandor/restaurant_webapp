<?php

namespace App\Http\Controllers;

use App\Models\GlobalCharge;
use Illuminate\Http\Request;

class GlobalChargeController extends Controller
{
    // Globális díjak listázása az admin felületen.

    public function index()
    {
        $charges = GlobalCharge::orderBy('created_at', 'desc')->get();

        return view('admin.global_charges.index', compact('charges'));
    }

    // Új díj létrehozása

    public function create()
    {
        return view('admin.global_charges.create');
    }

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

        GlobalCharge::create($validated);


        return redirect()->route('global-charges.index')->with('success', 'Díj sikeresen létrehozva!');
    }

    // Globális díjak szerkesztése

    public function edit($id)
    {
        $charge = GlobalCharge::findOrFail($id);

        return view('admin.global_charges.edit', compact('charge'));
    }


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

}
