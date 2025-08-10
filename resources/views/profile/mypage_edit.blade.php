@extends('layout')

@section('content')
<div class="container py-4">
    {{-- 🧑‍💼 Oldalcím --}}
    <h2>Profil szerkesztése</h2>

    {{-- ⚠️ Figyelmeztetés ideiglenes jelszóra --}}
    @if($user->must_change_password)
        <div class="alert alert-warning">
            <i class="fa-solid fa-key me-1"></i>
            A jelszavad ideiglenes. Kérjük, mielőbb állíts be saját jelszót a biztonság érdekében!
        </div>
    @endif

    {{-- ✅ Sikeres frissítés visszajelzése --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 📝 Profiladatok frissítése --}}
    <form method="POST" action="{{ route('mypage.update') }}">
        @csrf {{-- 🔐 CSRF token --}}

        {{-- 🔤 Név mező --}}
        <div class="mb-3">
            <label for="name">Név</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   class="form-control">
        </div>

        {{-- 📧 Email mező --}}
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   class="form-control">
        </div>

        {{-- 📱 Telefonszám mező --}}
        <div class="mb-3">
            <label for="phone">Telefonszám</label>
            <input type="text"
                   name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   class="form-control">
        </div>

        {{-- 🏡 Szállítási cím szekció --}}
        <hr>
        <h5 class="mt-4">Szállítási cím</h5>

        {{-- 📮 Irányítószám --}}
        <div class="mb-3">
            <label for="postal_code">Irányítószám</label>
            <input type="text"
                   name="postal_code"
                   value="{{ old('postal_code', $user->postal_code) }}"
                   class="form-control"
                   maxlength="10">
        </div>

        {{-- 🏙️ Település --}}
        <div class="mb-3">
            <label for="city">Település</label>
            <input type="text"
                   name="city"
                   value="{{ old('city', $user->city) }}"
                   class="form-control"
                   maxlength="50">
        </div>

        {{-- 🛣️ Közterület neve --}}
        <div class="mb-3">
            <label for="street_name">Közterület neve</label>
            <input type="text"
                   name="street_name"
                   value="{{ old('street_name', $user->street_name) }}"
                   class="form-control"
                   maxlength="100">
        </div>

        {{-- 🏠 Házszám --}}
        <div class="mb-3">
            <label for="street_number">Házszám</label>
            <input type="text"
                   name="street_number"
                   value="{{ old('street_number', $user->street_number) }}"
                   class="form-control"
                   maxlength="10">
        </div>

        {{-- 💾 Mentés gomb --}}
        <button type="submit" class="btn btn-primary">Mentés</button>
    </form>

    {{-- 🔐 Jelszómódosítás szekció --}}
    <hr class="my-4">
    <h4>Jelszómódosítás</h4>

    {{-- ✅ Sikeres jelszófrissítés visszajelzése --}}
    @if(session('success_password'))
        <div class="alert alert-success">{{ session('success_password') }}</div>
    @endif

    {{-- 🔒 Jelszó frissítő űrlap --}}
    <form method="POST" action="{{ route('mypage.password') }}">
        @csrf

        {{-- 🔐 Új jelszó --}}
        <div class="mb-3">
            <label for="password">Új jelszó</label>
            <input type="password"
                   name="password"
                   class="form-control">
        </div>

        {{-- 🔐 Jelszó megerősítése --}}
        <div class="mb-3">
            <label for="password_confirmation">Új jelszó megerősítése</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control">
        </div>

        {{-- ℹ️ Jelszó követelmények --}}
        <small class="text-muted d-block mb-3">
            A jelszónak legalább 8 karakterből kell állnia, tartalmazzon betűt és számot.
        </small>

        {{-- 🔄 Jelszó frissítése gomb --}}
        <button type="submit" class="btn btn-warning">Jelszó frissítése</button>
    </form>
</div>
@endsection
