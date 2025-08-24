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
    <input type="email" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           required value="{{ old('email') }}" placeholder="Pl. tesztuser@example.com">
    <small class="form-text text-muted">
        Legalább 5, legfeljebb 60 karakter. Csak betűk, számok, pont és @ karakter engedélyezett.
    </small>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

        {{-- 🔑 Jelszó mező --}}
        <div class="mb-3">
    <label for="password" class="form-label">Jelszó</label>
    <input type="password" name="password" id="password"
           class="form-control @error('password') is-invalid @enderror" required>
    <small class="form-text text-muted">
        Legalább 8, legfeljebb 36 karakter. Csak kis- és nagybetűk, valamint számok. Példa: Teszt1234
    </small>
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

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
