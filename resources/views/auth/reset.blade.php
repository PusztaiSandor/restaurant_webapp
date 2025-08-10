@extends('layout') {{-- 🌐 Alap layout betöltése --}}

@section('content')
<div class="container py-4">
    {{-- 🧾 Oldalcím --}}
    <h2 class="text-center mb-4">Új jelszó megadása</h2>

    {{-- ⚠️ Validációs hibák megjelenítése --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li> {{-- 🔁 Több hiba esetén felsorolás --}}
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🔐 Jelszófrissítő űrlap --}}
    <form method="POST" action="{{ route('confirm-reset.post', ['email' => $email]) }}">
        @csrf {{-- 🛡️ Laravel CSRF token a biztonságos POST kéréshez --}}

        {{-- 🔑 Új jelszó mező --}}
        <div class="mb-3">
            <label for="password" class="form-label">Új jelszó</label>
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- 🔁 Jelszó megerősítése --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control" required>
        </div>

        {{-- ✅ Küldés gomb --}}
        <button type="submit" class="btn btn-success w-100 mt-3">Jelszó módosítása</button>
    </form>
</div>
@endsection
