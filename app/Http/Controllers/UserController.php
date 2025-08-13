<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class UserController extends Controller
{
    /**
     * 🧑‍💼 Megjeleníti a profil főoldalt, ahol a felhasználó
     * szerkesztheti az adatait és módosíthatja a jelszavát.
     * A nézet: 'profile.mypage_edit'
     */
    public function show()
    {
        $user = Auth::user(); // 🔐 Bejelentkezett felhasználó lekérése
        return view('profile.mypage_edit', compact('user'));
    }

    /**
     * 🛠️ Megjeleníti az e-mail és jelszó frissítő nézetet.
     * Ez egy különálló szerkesztő oldal.
     * A nézet: 'profile.edit'
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * 📝 Profiladatok frissítése (név, email, cím stb.).
     * Validálja a bemenetet, majd menti az új adatokat.
     * Visszairányít a fő szerkesztő oldalra.
     */
    public function update(Request $request)
{
    // ✅ Validációs szabályok
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::user()->users_id . ',users_id', // ← Vessző a végére!
        'phone' => 'nullable|string|max:30',
        'postal_code' => 'nullable|string|max:10',
        'city' => 'nullable|string|max:50',
        'street_name' => 'nullable|string|max:100',
        'street_number' => 'nullable|string|max:10',
    ]);

    // 🧑‍💻 Felhasználó frissítése
    $user = Auth::user();
    $user->update($validated);

    // 🔔 Sikeres frissítés után visszairányítás
    return redirect()->route('profile')->with('success', 'Profilod frissítve!');
}

    /**
     * Jelszómódosítás feldolgozása – titkosítással
     */
    public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed'
    ]);

    $user = Auth::user();
    $user->password = Hash::make($request->password);

    // 🔓 Ha korábban ideiglenes jelszóval lépett be, megszüntetjük a jelzést
    if ($user->must_change_password) {
        $user->must_change_password = false;
    }

    $user->save();

    return back()->with('success_password', 'Jelszavad sikeresen frissítve!');
}

public function updateCredentials(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email,' . Auth::user()->users_id . ',users_id',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->must_change_password = false;
    $user->save();

    return redirect()->route('home');
}

    /**
     * 📦 Rendelések megjelenítése a felhasználó számára.
     * Betölti az összes rendelést és azok kapcsolt elemeit.
     * A nézet: 'profile.orders'
     */
    public function orders()
    {
        $user = Auth::user();

        // 🧾 Rendelések lekérése kapcsolt elemekkel
        $orders = $user->orders()
            ->with([
                'items' => function ($query) {
                    $query->with('dish'); // 🍽️ Ételek betöltése az elemekhez
                }
            ])
            ->orderByDesc('created_at') // 🕒 Legfrissebb elöl
            ->get();

        return view('mypage.orders', compact('orders'));
    }
}
