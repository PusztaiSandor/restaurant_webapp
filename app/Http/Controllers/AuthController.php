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
     * 📄 Regisztrációs űrlap megjelenítése
     *
     * Ez a metódus visszaadja a regisztrációs nézetet.
     * A felhasználó itt tudja megadni az adatait.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * 📝 Regisztrációs adatok feldolgozása
     *
     * Validálja a beküldött adatokat, létrehozza az új felhasználót,
     * majd automatikusan bejelentkezteti és irányítja a szerepkör szerint.
     */
    public function register(Request $request)
    {
        // 📋 Adatok validálása
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:50',
            'street_name' => 'nullable|string|max:100',
            'street_number' => 'nullable|string|max:10',
            'role' => 'required|in:user,admin,courier'
        ]);

        // 👤 Új felhasználó létrehozása
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // 🔐 Jelszó titkosítása
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'postal_code' => $validated['postal_code'],
            'city' => $validated['city'],
            'street_name' => $validated['street_name'],
            'street_number' => $validated['street_number'],
            'active' => 1, // ✅ Legyen aktív
        ]);

        // 🔑 Automatikus bejelentkeztetés
        Auth::login($user);
        $user->last_login_at = now();
        $user->save();

        // 🧭 Irányítás szerepkör szerint
        return $this->redirectBasedOnRole($user)->with('success', 'Sikeres regisztráció!');
    }

    /**
     * 🔐 Bejelentkezési űrlap megjelenítése
     *
     * Visszaadja a login nézetet, ahol a felhasználó megadhatja az e-mailt és jelszót.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 🔓 Bejelentkezési adatok feldolgozása
     *
     * Ellenőrzi az e-mail és jelszó párost, majd irányítja a felhasználót
     * a szerepkörének megfelelő oldalra.
     */
    public function login(Request $request)
{
    // 📋 Beviteli adatok validálása
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 👤 Felhasználó lekérése az e-mail alapján
    $user = User::where('email', $request->email)->first();

    // ❌ Ha nincs ilyen felhasználó vagy nem aktív
    if (!$user || !$user->active) {
        return back()->with('error', 'A fiókod jelenleg nem aktív. Kérjük, vedd fel a kapcsolatot az Adminnal.')
                     ->withInput(); // 🔁 Visszatöltés a formba
    }

    // 🔑 Hitelesítési próbálkozás (csak ha aktív)
    $successful = Auth::attempt(
        ['email' => $request->email, 'password' => $request->password],
        $request->filled('remember') // „Emlékezzen rám” opció
    );

    // ✅ Sikeres bejelentkezés
    if ($successful) {
        $user = Auth::user();

        // 🕒 Bejelentkezési idő mentése
        $user->last_login_at = now();
        $user->save();

        // ⚠️ Kötelező jelszó/email módosítás ellenőrzése
        if ($user->must_change_password) {
            return redirect()->route('profile.edit')
                ->with('info', 'Kérlek, módosítsd a jelszavad és email címed!');
        }

        // 🧭 Irányítás szerepkör szerint
        return $this->redirectBasedOnRole($user);
    }

    // ❌ Hibás jelszó
    return back()->with('error', 'Hibás e-mail vagy jelszó!')->withInput();
}

    /**
     * 🧭 Irányítás szerepkör szerint
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
 * 📧 Jelszóemlékeztető szimuláció
 *
 * Ellenőrzi, hogy létezik-e a megadott e-mail, és hogy a fiók aktív-e.
 * Ha aktív, megjeleníti a szimulált e-mail nézetet.
 * Ha nem aktív, visszairányítja a bejelentkezési felületre hibaüzenettel.
 */
public function simulateReset(Request $request)
{
    // 📋 E-mail mező validálása
    $request->validate(['email' => 'required|email']);

    // 👤 Felhasználó lekérése az e-mail alapján
    $user = User::where('email', $request->email)->first();

    // ❌ Ha nincs ilyen felhasználó
    if (!$user) {
        return back()->withErrors(['email' => 'Nincs ilyen e-mail cím regisztrálva.'])->withInput();
    }

    // ❌ Ha a felhasználó nem aktív
    if (!$user->active) {
        return redirect()->route('login')
            ->with('error', 'A fiókod jelenleg nem aktív. Kérjük, vedd fel a kapcsolatot az Adminnal.');
    }

    // ✅ Aktív fiók esetén megjelenítjük a szimulált e-mail nézetet
    return view('auth.simulated_email', ['email' => $request->email]);
}

    /**
     * 🔁 Jelszóemlékeztető űrlap megjelenítése
     *
     * A felhasználó itt tud új jelszót megadni a korábban megadott e-mail alapján.
     */
    public function showResetForm($email)
    {
        return view('auth.reset', ['email' => $email]);
    }

    /**
     * 🔄 Jelszó frissítése
     *
     * A megadott e-mail címhez tartozó felhasználó jelszavát frissíti.
     */
    public function updatePassword(Request $request, $email)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/login')->withErrors(['email' => 'Nem található felhasználó.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')->with('success', 'A jelszavad sikeresen módosítva lett!');
    }

    /**
     * ❓ Elfelejtett jelszó nézet
     *
     * A felhasználó itt tudja megadni az e-mail címét jelszóemlékeztető céljából.
     */
    public function showForgotForm()
    {
        return view('auth.forgot');
    }
}
