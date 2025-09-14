<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    // Felhasználók listázása

    public function index()
    {
        // Csak admin jogosultsággal elérhető
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Nincs jogosultságod az admin felülethez.');
        }

        $users = User::orderBy('name')->get();

        return view('admin.users.index', compact('users'));

    }


    // Új felhasználó létrehozása:

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű.\- ]+$/u',
            ],
            'role' => 'required|in:admin,courier',
        ], [
            'name.required' => 'A név megadása kötelező.',
            'name.min' => 'A név legalább 2 karakter hosszú legyen.',
            'name.max' => 'A név legfeljebb 50 karakter lehet.',
            'name.regex' => 'A név csak betűket, szóközt, pontot és kötőjelet tartalmazhat. Példa: Kiss-Kovács János',
        ]);

        // Ideiglenes e-mail generálása a névből + véletlenszerű szám.
        $baseEmail = Str::slug($validated['name'], '.');
        $suffix = rand(1000, 9999);
        $email = "{$baseEmail}.{$suffix}@esszencia.local";

        // Ideiglene jelszó generálása és titkosítása.
        $rawPassword = 'Esszencia2025'.$suffix;
        $hashedPassword = Hash::make($rawPassword);
        $hintHash = Hash::make($rawPassword);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => $hashedPassword,
            'password_hint' => $hintHash,
            'must_change_password' => true,
            'role' => $validated['role'],
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Felhasználó létrehozva • email: {$email} • jelszó: {$rawPassword}");
    }

    // Ideiglenes jelszó és email újragenerálása egy felhasználónak.
    // Csak akkor engedélyezett, ha még nem változtatta meg a jelszavát.
    public function regeneratePassword($id)
    {
        $user = User::findOrFail($id);

        $hasTemporaryAccess = Str::endsWith($user->email, '@esszencia.local') && $user->must_change_password;

        if (! $hasTemporaryAccess) {
            return redirect()->route('admin.users.edit', $user->users_id)
                ->with('error', 'Ez a felhasználó már nem rendelkezik ideiglenes hozzáféréssel.');
        }

        // Új ideiglenes jelszó és email generálása
        $suffix = rand(1000, 9999);
        $rawPassword = 'Esszencia2025'.$suffix;
        $hashed = Hash::make($rawPassword);
        $hintHash = Hash::make($rawPassword);

        $baseEmail = Str::slug($user->name, '.');
        $newEmail = "{$baseEmail}.{$suffix}@esszencia.local";


        $user->update([
            'email' => $newEmail,
            'password' => $hashed,
            'password_hint' => $hintHash,
            'must_change_password' => true,
        ]);


        return redirect()->route('admin.users.edit', $user->users_id)
            ->with('success_password', "Új ideiglenes jelszó generálva: {$newEmail} • jelszó: {$rawPassword}");
    }

    // Felhasználói adatok módosítása

    public function edit(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'min:5',
                'max:60',
                'unique:users,email,'.$user->users_id.',users_id',
                'regex:/^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/',
            ],
            'role' => 'required|in:courier,admin',
        ], [
            'name.min' => 'A név legalább 2 karakter hosszú legyen.',
            'name.max' => 'A név legfeljebb 50 karakter lehet.',
            'name.regex' => 'A név csak betűket, szóközt, pontot és kötőjelet tartalmazhat. Példa: Kiss-Kovács János',
            'email.required' => 'Az e-mail cím megadása kötelező.',
            'email.email' => 'Az e-mail cím formátuma nem megfelelő.',
            'email.min' => 'Az e-mail cím legalább 5 karakter hosszú legyen.',
            'email.max' => 'Az e-mail cím legfeljebb 60 karakter lehet.',
            'email.unique' => 'Ez az e-mail cím már regisztrálva van.',
            'email.regex' => 'Az e-mail cím csak betűket, számokat, pontot, kötőjelet, aláhúzást és @ karaktert tartalmazhat. Példa: kiss_auto@example.hu',
        ]);


        $user->update([
            'email' => $validated['email'],
            'role' => $validated['role'],
            'active' => $request->has('active'),
        ]);


        return redirect()->route('admin.users.index')
            ->with('success', 'Felhasználó adatai sikeresen módosítva.');
    }

    // Felhasználó aktiválása vagy inaktiválása.
    // A státuszt megfordítjuk, majd mentjük.
    public function toggleStatus(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user->active = ! $user->active;
        $user->save();

        $state = $user->active ? 'aktiválva' : 'inaktiválva';

        return redirect()->route('admin.users.index')
            ->with('success', "Felhasználó sikeresen {$state}.");
    }
}
