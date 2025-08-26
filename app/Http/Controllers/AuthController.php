<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Regisztrációs űrlap megjelenítése
     *
     * Ez a metódus visszaadja a regisztrációs nézetet.
     * A felhasználó itt tudja megadni az adatait.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Regisztrációs adatok feldolgozása
     *
     * Validálja a beküldött adatokat, létrehozza az új felhasználót,
     * majd automatikusan bejelentkezteti és irányítja a szerepkör szerint.
     */
    public function register(Request $request)
{
    // Adatok validálása
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
            'unique:users,email',
            'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/'
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'max:36',
            'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$/',
            'confirmed'
        ],
        'role' => 'required|in:user,admin,courier', //Csak a teszt üzem miatt
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
        // Magyar hibaüzenetek
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
        'password.required' => 'A jelszó megadása kötelező.',
        'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
        'password.max' => 'A jelszó legfeljebb 36 karakter lehet.',
        'password.regex' => 'A jelszónak tartalmaznia kell betűt és számot, és csak betűket és számokat tartalmazhat.',
        'password.confirmed' => 'A jelszó megerősítése nem egyezik.',
        'phone.regex' => 'A telefonszám formátuma csak a következő lehet: +36-20|30|40|70-123-4567',
        'postal_code.regex' => 'Az irányítószámnak pontosan 4 számjegyből kell állnia. Példa: 1139',
        'city.regex' => 'A település neve csak betűket, szóközt és kötőjelet tartalmazhat. Példa: Budapest vagy Dunakeszi-Alag',
        'street_name.regex' => 'A közterület neve csak betűket, számokat, szóközt, pontot és kötőjelet tartalmazhat. Példa: 10. kerület vagy 27. utca',
        'street_number.regex' => 'A házszám csak számokat, betűket, kötőjelet és perjelet tartalmazhat. Példa: 15/A vagy 13–15',
    ]);

    // Új felhasználó létrehozása
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'phone' => $validated['phone'],
        'role' => $validated['role'], //Csak a teszt üzem miatt
        'postal_code' => $validated['postal_code'],
        'city' => $validated['city'],
        'street_name' => $validated['street_name'],
        'street_number' => $validated['street_number'],
        'active' => 1,
    ]);

    // Automatikus bejelentkeztetés
    Auth::login($user);
    $user->last_login_at = now();
    $user->save();

    // 🧭 Irányítás szerepkör szerint
    return $this->redirectBasedOnRole($user)->with('success', 'Sikeres regisztráció!');
}

    /**
     * Bejelentkezési űrlap megjelenítése
     *
     * Visszaadja a login nézetet, ahol a felhasználó megadhatja az e-mailt és jelszót.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Bejelentkezési adatok feldolgozása
     *
     * Ellenőrzi az e-mail és jelszó párost, majd irányítja a felhasználót
     * a szerepkörének megfelelő oldalra.
     */
    public function login(Request $request)
{
    // Beviteli adatok validálása
    $request->validate([
    'email' => [
        'required',
        'string',
        'email',
        'min:5',
        'max:60',
        'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/'
    ],
    'password' => [
        'required',
        'string',
        'min:8',
        'max:36',
        'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$/'
    ],
], [
   // Magyar hibaüzenetek
    'email.required' => 'Az e-mail cím megadása kötelező.',
    'email.email' => 'Az e-mail cím formátuma nem megfelelő.',
    'email.min' => 'Az e-mail cím legalább 5 karakter hosszú legyen.',
    'email.max' => 'Az e-mail cím legfeljebb 60 karakter lehet.',
    'email.regex' => 'Az e-mail cím csak betűket, számokat, pontot, kötőjelet, aláhúzást és @ karaktert tartalmazhat. Példa: kiss_auto@example.hu',
    'password.required' => 'A jelszó megadása kötelező.',
    'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
    'password.max' => 'A jelszó legfeljebb 36 karakter lehet.',
    'password.regex' => 'A jelszónak tartalmaznia kell betűt és számot, és csak betűket és számokat tartalmazhat.',
]);

    // Felhasználó lekérése az e-mail alapján
    $user = User::where('email', $request->email)->first();

    // Ha nincs ilyen felhasználó vagy nem aktív
    if (!$user || !$user->active) {
        return back()->with('error', 'A fiókod jelenleg nem aktív. Kérjük, vedd fel a kapcsolatot az Adminnal.')
                     ->withInput(); // 🔁 Visszatöltés a formba
    }

    // Hitelesítési próbálkozás (csak ha aktív)
    $successful = Auth::attempt(
        ['email' => $request->email, 'password' => $request->password],
        $request->filled('remember') // „Emlékezzen rám” opció
    );

    // Sikeres bejelentkezés
    if ($successful) {
        $user = Auth::user();

        // 🕒 Bejelentkezési idő mentése
        $user->last_login_at = now();
        $user->save();

        // Kötelező jelszó/email módosítás ellenőrzése
        if ($user->must_change_password) {
            return redirect()->route('profile.edit')
                ->with('info', 'Kérlek, módosítsd a jelszavad és email címed!');
        }

        // Irányítás szerepkör szerint
        return $this->redirectBasedOnRole($user);
    }

    // Hibás jelszó
    return back()->with('error', 'Hibás e-mail vagy jelszó!')->withInput();
}

    /**
     * Irányítás szerepkör szerint
     *
     * A bejelentkezett felhasználót a szerepkörének megfelelő oldalra irányítja.
     */
    protected function redirectBasedOnRole(User $user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('home')->with('success', 'Sikeres belépés (admin)!');
            case 'courier':
                return redirect()->route('home')->with('success', 'Sikeres belépés (futár)!');
            default:
                return redirect()->route('home')->with('success', 'Sikeres belépés!');
        }
    }

    /**
 * Jelszóemlékeztető szimuláció
 *
 * Ellenőrzi, hogy létezik-e a megadott e-mail, és hogy a fiók aktív-e.
 * Ha aktív, megjeleníti a szimulált e-mail nézetet.
 * Ha nem aktív, visszairányítja a bejelentkezési felületre hibaüzenettel.
 */
public function simulateReset(Request $request)
{
    // E-mail mező validálása
    $request->validate([
    'email' => [
        'required',
        'string',
        'email',
        'min:5',
        'max:60',
        'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/'
    ]
    // Magyar hibaüzenetek
], [
    'email.required' => 'Az e-mail cím megadása kötelező.',
    'email.email' => 'Az e-mail cím formátuma nem megfelelő.',
    'email.min' => 'Az e-mail cím legalább 5 karakter hosszú legyen.',
    'email.max' => 'Az e-mail cím legfeljebb 60 karakter lehet.',
    'email.regex' => 'Az e-mail cím csak betűket, számokat, pontot, kötőjelet, aláhúzást és @ karaktert tartalmazhat. Példa: kiss_auto@example.hu',
]);

    // Felhasználó lekérése az e-mail alapján
    $user = User::where('email', $request->email)->first();

    // Ha nincs ilyen felhasználó
    if (!$user) {
        return back()->withErrors(['email' => 'Nincs ilyen e-mail cím regisztrálva.'])->withInput();
    }

    // Ha a felhasználó nem aktív
    if (!$user->active) {
        return redirect()->route('login')
            ->with('error', 'A fiókod jelenleg nem aktív. Kérjük, vedd fel a kapcsolatot az Adminnal.');
    }

    // Aktív fiók esetén megjelenítjük a szimulált e-mail nézetet
    return view('auth.simulated_email', ['email' => $request->email]);
}

    /**
     * Jelszóemlékeztető űrlap megjelenítése
     *
     * A felhasználó itt tud új jelszót megadni a korábban megadott e-mail alapján.
     */
    public function showResetForm($email)
    {
        return view('auth.reset', ['email' => $email]);
    }

    /**
     * Jelszó frissítése
     *
     * A megadott e-mail címhez tartozó felhasználó jelszavát frissíti.
     */
    public function updatePassword(Request $request, $email)
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
    // Magyar hibaüzenetek
], [
    'password.required' => 'A jelszó megadása kötelező.',
    'password.min' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
    'password.max' => 'A jelszó legfeljebb 36 karakter lehet.',
    'password.regex' => 'A jelszónak tartalmaznia kell betűt és számot, és csak betűket és számokat tartalmazhat.',
    'password.confirmed' => 'A jelszó megerősítése nem egyezik.',
]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Nem található felhasználó.']);
        }

        //  Új jelszó ne egyezzen a régivel
    if (Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Az új jelszó nem lehet azonos a jelenlegi jelszóval.']);
    }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')->with('success', 'A jelszavad sikeresen módosítva lett!');
    }

    /**
     * Elfelejtett jelszó nézet
     *
     * A felhasználó itt tudja megadni az e-mail címét jelszóemlékeztető céljából.
     */
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    // Kilépés
    public function logout(Request $request)
{
    Auth::logout(); // Felhasználó kijelentkeztetése

    // Session ürítése (opcionális)
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Sikeresen kiléptél.');
}
}
