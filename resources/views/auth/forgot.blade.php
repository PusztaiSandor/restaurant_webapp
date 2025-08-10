@extends('layout') {{-- 🌐 Alap layout betöltése --}}

@section('content')
<div class="container py-4">
    {{-- 🧾 Oldalcím --}}
    <h2 class="text-center mb-4">Elfelejtetted a jelszót?</h2>

    {{-- ⚠️ Hibás e-mail esetén megjelenő hibaüzenet --}}
    @if ($errors->has('email'))
        <div class="alert alert-danger text-center">
            {{ $errors->first('email') }}
        </div>
    @endif

    {{-- 📤 Jelszóemlékeztető kérő űrlap --}}
    <form method="POST" action="{{ route('forgot-password.post') }}">
        @csrf {{-- 🛡️ Laravel CSRF token a biztonságos POST kéréshez --}}

        {{-- 📧 E-mail mező --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mail cím</label>
            <input type="email" name="email" id="email" class="form-control"
                   required value="{{ old('email') }}"> {{-- 🔁 Hibás beküldés után visszatöltés --}}
        </div>

        {{-- 🚀 Küldés gomb --}}
        <button type="submit" class="btn btn-primary w-100">Jelszó visszaállítása</button>
    </form>
</div>
@endsection
