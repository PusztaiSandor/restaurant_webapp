@extends('layout')

@section('content')
<div class="container">
    <h2>Felhasználó szerkesztése</h2>

    {{-- Validációs hibák megjelenítése --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Flash üzenetek: sikeres mentés vagy jelszó generálás --}}
    {{-- @foreach (['success', 'success_password'] as $type)
        @if (session($type))
            <div class="alert alert-{{ $type === 'success' ? 'success' : 'info' }}">
                <i class="fa-solid fa-{{ $type === 'success' ? 'check-circle' : 'key' }} me-1"></i>
                {{ session($type) }}
            </div>
        @endif
    @endforeach --}}

    @if (session('success_password'))
    <div class="alert alert-info">
        <i class="fa-solid fa-key me-1"></i>
        {{ session('success_password') }}
    </div>
    @endif

    {{-- Felhasználó szerkesztő űrlap --}}
    <form method="POST" action="{{ route('admin.users.update', $user->users_id) }}">
        @csrf
        @method('PUT')

        @php
            // Ideiglenes hozzáférés ellenőrzése
            $hasTemporaryAccess = Str::endsWith($user->email, '@esszencia.local') && $user->must_change_password;
        @endphp

        {{-- Név mező --}}
        <div class="mb-3">
            <label for="name">Név</label>
            <input type="text"
       name="name"
       id="name"
       class="form-control"
       value="{{ old('name', $user->name) }}"
       required
       minlength="2"
       maxlength="50"
       pattern="^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű.\- ]+$"
       placeholder="Pl. Kiss-Kovács János"
       readonly>
<small class="form-text text-muted">
    A név csak a felhasználó saját profiljában módosítható.
</small>
        </div>

        {{-- Email mező + ideiglenes hozzáférés figyelmeztetés --}}
        <div class="mb-3">
            <label for="email">Email cím</label>
            <input type="email"
       name="email"
       id="email"
       class="form-control"
       value="{{ old('email', $user->email) }}"
       required
       minlength="5"
       maxlength="60"
       pattern="^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$"
       placeholder="Pl. kiss_auto@example.hu">
<small class="form-text text-muted">
    Betűk, számok, pont, kötőjel, aláhúzás és @ karakter engedélyezett. Minimum 5, maximum 60 karakter.
</small>

            @if($hasTemporaryAccess)
                <div class="alert alert-warning d-inline-block py-2 px-3 mt-2">
                    <strong>IDEIGLENES hozzáférés aktív</strong>
        <span class="ms-2">Javasolt email és jelszó frissítés</span>
                </div>
            @endif
        </div>

        {{-- Szerepkör kiválasztása --}}
        <div class="mb-3">
            <label for="role">Szerepkör</label>
            <select name="role" id="role" class="form-select">
                @foreach(['courier', 'admin'] as $role)
                    <option value="{{ $role }}" @selected($user->role === $role)>
                        {{ (new \App\Models\User(['role' => $role]))->getRoleLabel() }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Aktív állapot checkbox --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" @checked($user->active)>
            <label for="active" class="form-check-label">Aktív állapot</label>
        </div>

        {{-- Mentés és vissza gombok --}}
        <button type="submit" class="btn btn-primary">💾 Mentés</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">⬅️ Vissza</a>
    </form>

    {{-- Ideiglenes hozzáférés esetén jelszó és email újragenerálása --}}
    @if($hasTemporaryAccess)
        <form method="POST" action="{{ route('admin.users.regeneratePassword', $user->users_id) }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                Új ideiglenes jelszó és email generálása
            </button>
        </form>
    @endif
</div>
@endsection
