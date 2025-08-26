<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class UserController extends Controller
{
    /**
     * Megjeleníti a profil főoldalt, ahol a felhasználó
     * szerkesztheti az adatait és módosíthatja a jelszavát.
     * A nézet: 'profile.mypage_edit'
     */
    public function show()
    {
        $user = Auth::user(); // Bejelentkezett felhasználó lekérése
        return view('profile.mypage', compact('user'));
    }

    public function editMypage()
{
    $user = Auth::user(); // Bejelentkezett felhasználó lekérése
    return view('profile.mypage_edit', compact('user'));
}

    /**
     * Megjeleníti az e-mail és jelszó frissítő nézetet.
     * Ez egy különálló szerkesztő oldal.
     * A nézet: 'profile.edit'
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Profiladatok frissítése (név, email, cím stb.).
     * Validálja a bemenetet, majd menti az új adatokat.
     * Visszairányít a fő szerkesztő oldalra.
     */
    public function update(Request $request)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'min:2',
            'max:50',
            'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű.\- ]+$/u'
        ],
        'email' => [
            'required',
            'string',
            'email',
            'min:5',
            'max:60',
            'unique:users,email,' . Auth::user()->users_id . ',users_id',
            'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/'
        ],
        'phone' => [
            'nullable',
            'string',
            'regex:/^\+36-(20|30|40|70)-\d{3}-\d{4}$/'
        ],
        'postal_code' => [
            'nullable',
            'string',
            'regex:/^\d{4}$/'
        ],
        'city' => [
            'nullable',
            'string',
            'max:50',
            'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű -]{1,50}$/u'
        ],
        'street_name' => [
            'nullable',
            'string',
            'max:100',
            'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 .-]{1,100}$/u'
        ],
        'street_number' => [
            'nullable',
            'string',
            'max:10',
            'regex:/^[0-9A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\/\-]{1,10}$/u'
        ],
    ], [
        'name.required' => 'A név megadása kötelező.',
        'name.min' => 'A név legalább 2 karakter hosszú legyen.',
        'name.max' => 'A név legfeljebb 50 karakter lehet.',
        'name.regex' => 'A név csak betűket, szóközt, pontot és kötőjelet tartalmazhat. Példa: Kiss-Kovács János',
        'email.required' => 'Az e-mail cím megadása kötelező.',
        'email.email' => 'Az e-mail cím formátuma nem megfelelő.',
        'email.min' => 'Az e-mail cím legalább 5 karakter hosszú legyen.',
        'email.max' => 'Az e-mail cím legfeljebb 60 karakter lehet.',
        'email.unique' => 'Ez az e-mail cím már regisztrálva van.',
        'email.regex' => 'Az e-mail cím csak betűket, számokat, pontot, kötőjelet, aláhúzást és @ karaktert tartalmazhat. Példa: kiss_auto@example.hu',
        'phone.regex' => 'A telefonszám formátuma csak a következő lehet: +36-20|30|40|70-123-4567',
        'postal_code.regex' => 'Az irányítószámnak pontosan 4 számjegyből kell állnia. Példa: 1139',
        'city.regex' => 'A település neve csak betűket, szóközt és kötőjelet tartalmazhat. Példa: Budapest vagy Dunakeszi-Alag',
        'street_name.regex' => 'A közterület neve csak betűket, számokat, szóközt, pontot és kötőjelet tartalmazhat. Példa: 10. kerület vagy 27. utca',
        'street_number.regex' => 'A házszám csak számokat, betűket, kötőjelet és perjelet tartalmazhat. Példa: 15/A vagy 13–15',
    ]);

    // Felhasználó frissítése
    $user = Auth::user();
    $user->update($validated);

    // Sikeres frissítés után visszairányítás
    return redirect()->route('profile')->with('success', 'Profilod frissítve!');
}

    /**
     * Jelszómódosítás feldolgozása – titkosítással
     */
    public function updatePassword(Request $request)
{
    $request->validate([
    'password' => [
        'required',
        'string',
        'min:8',
        'max:36',
        'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$/',
        'confirmed'
    ]
], [
    'password.required' => 'A jelszó megadása kötelező.',
    'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
    'password.max' => 'A jelszó legfeljebb 36 karakter lehet.',
    'password.regex' => 'A jelszónak tartalmaznia kell betűt és számot, és csak betűket és számokat tartalmazhat.',
    'password.confirmed' => 'A jelszó megerősítése nem egyezik.',
]);

    $user = Auth::user();
    $user->password = Hash::make($request->password);

    // Ha korábban ideiglenes jelszóval lépett be, megszüntetjük a jelzést
    if ($user->must_change_password) {
        $user->must_change_password = false;
    }

    $user->save();

    return back()->with('success_password', 'Jelszavad sikeresen frissítve!');
}

public function updateCredentials(Request $request)
{
    $request->validate([
        'email' => [
            'required',
            'string',
            'email',
            'min:5',
            'max:60',
            'unique:users,email,' . Auth::user()->users_id . ',users_id',
            'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/'
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'max:36',
            'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$/',
            'confirmed'
        ]
    ], [
        'email.required' => 'Az e-mail cím megadása kötelező.',
        'email.email' => 'Az e-mail cím formátuma nem megfelelő.',
        'email.min' => 'Az e-mail cím legalább 5 karakter hosszú legyen.',
        'email.max' => 'Az e-mail cím legfeljebb 60 karakter lehet.',
        'email.unique' => 'Ez az e-mail cím már regisztrálva van.',
        'email.regex' => 'Az e-mail cím csak betűket, számokat, pontot, kötőjelet, aláhúzást és @ karaktert tartalmazhat. Példa: kiss_auto@example.hu',
        'password.required' => 'A jelszó megadása kötelező.',
        'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
        'password.max' => 'A jelszó legfeljebb 36 karakter lehet.',
        'password.regex' => 'A jelszónak tartalmaznia kell betűt és számot, és csak betűket és számokat tartalmazhat.',
        'password.confirmed' => 'A jelszó megerősítése nem egyezik.',
    ]);

    $user = Auth::user();
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->must_change_password = false;
    $user->save();

    return redirect()->route('home');
}

}
