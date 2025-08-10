@extends('layout') {{-- 🌐 Alap layout használata --}}

@section('content')
<div class="container py-4">
    {{-- 🧾 Oldalcím --}}
    <h2 class="text-center mb-4">Belépés</h2>

    {{-- ⚠️ Hibás bejelentkezés esetén megjelenő hibaüzenet --}}
    @if (session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    {{-- ✅ Sikeres regisztráció vagy jelszócsere után megjelenő üzenet --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- 🔐 Bejelentkezési űrlap --}}
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf {{-- 🛡️ Laravel CSRF token a biztonságos POST kéréshez --}}

        {{-- 📧 E-mail mező --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mail cím</label>
            <input type="email" name="email" id="email" class="form-control"
                   required value="{{ old('email') }}"> {{-- 🔁 Hibás beküldés után visszatöltés --}}
        </div>

        {{-- 🔑 Jelszó mező --}}
        <div class="mb-3">
            <label for="password" class="form-label">Jelszó</label>
            <input type="password" name="password" id="password" class="form-control" required>

            {{-- ❓ Elfelejtett jelszó link --}}
            <div class="text-end mt-2">
                <a href="{{ route('forgot-password') }}">Elfelejtettem a jelszavam</a>
            </div>
        </div>

        {{-- 🧠 „Emlékezzen rám” opció --}}
        <div class="form-check mb-3">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Emlékezzen rám</label>
        </div>

        {{-- 🚪 Belépés gomb --}}
        <button type="submit" class="btn btn-primary w-100">Belépés</button>
    </form>
</div>
@endsection
