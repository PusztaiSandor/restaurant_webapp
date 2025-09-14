<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Saját profil megjelenítése, szerkesztése

    public function show()
    {
        $user = Auth::user();

        return view('profile.mypage', compact('user'));
    }

    public function editMypage()
    {
        $user = Auth::user();

        return view('profile.mypage_edit', compact('user'));
    }


    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű.\- ]+$/u',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'min:5',
                'max:60',
                'unique:users,email,'.Auth::user()->users_id.',users_id',
                'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^\+36-(20|30|40|70)-\d{3}-\d{4}$/',
            ],
            'postal_code' => [
                'required',
                'string',
                'regex:/^\d{4}$/',
            ],
            'city' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű -]{1,50}$/u',
            ],
            'street_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 .-]{1,100}$/u',
            ],
            'street_number' => [
                'required',
                'string',
                'max:10',
                'regex:/^[0-9A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\/\-]{1,10}$/u',
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
            'phone.required' => 'A telefonszám megadása kötelező.',
            'phone.regex' => 'A telefonszám formátuma csak a következő lehet: +36-20|30|40|70-123-4567',
            'postal_code.required' => 'Az irányítószám megadása kötelező.',
            'postal_code.regex' => 'Az irányítószámnak pontosan 4 számjegyből kell állnia. Példa: 1139',
            'city.required' => 'A település név megadása kötelező.',
            'city.regex' => 'A település neve csak betűket, szóközt és kötőjelet tartalmazhat. Példa: Budapest vagy Dunakeszi-Alag',
            'street_name.required' => 'A közterület név megadása kötelező.',
            'street_name.regex' => 'A közterület neve csak betűket, számokat, szóközt, pontot és kötőjelet tartalmazhat. Példa: 10. kerület vagy 27. utca',
            'street_number.required' => 'A házszám megadása kötelező.',
            'street_number.regex' => 'A házszám csak számokat, betűket, kötőjelet és perjelet tartalmazhat. Példa: 15/A vagy 13-15',
        ]);


        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profilod frissítve!');
    }

    // Jelszómódosítás feldolgozása, titkosítással.

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'max:36',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$/',
                'confirmed',
            ],
        ], [
            'password.required' => 'A jelszó megadása kötelező.',
            'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
            'password.max' => 'A jelszó legfeljebb 36 karakter lehet.',
            'password.regex' => 'A jelszónak tartalmaznia kell betűt és számot, és csak betűket és számokat tartalmazhat.',
            'password.confirmed' => 'A jelszó megerősítése nem egyezik.',
        ]);

        $user = Auth::user();

        // Ne lehessen ugyanaz a jelszó
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Az új jelszó nem lehet azonos a jelenlegi jelszóval.']);
        }
        // Jelszó titkosítása és mentése
        $user->password = Hash::make($request->password);

        // Ha korábban ideiglenes jelszóval lépett be, megszüntetjük a jelzést
        if ($user->must_change_password) {
            $user->must_change_password = false;
        }

        $user->save();


        return back()->with('success_password', 'Jelszavad sikeresen módosítva!');
    }

    // E-mail és jelszó együttes frissítése.
    // Validáljuk mindkét mezőt, majd mentjük az új értékeket.
    public function updateCredentials(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'min:5',
                'max:60',
                'unique:users,email,'.Auth::user()->users_id.',users_id',
                'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:36',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$/',
                'confirmed',
            ],
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
