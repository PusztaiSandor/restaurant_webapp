@extends('layout')

@section('content')
<div class="container py-4">
    {{-- 🧾 Regisztrációs fejléc --}}
    <h2 class="text-center mb-4">Regisztráció</h2>

    {{-- ⚠️ Hibák megjelenítése --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 📤 Regisztrációs űrlap --}}
    <form method="POST" action="{{ route('register.submit') }}">
        @csrf

        {{-- 👤 Alapadatok --}}
        <div class="mb-3">
            <label for="name" class="form-label">Név</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail cím</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefonszám</label>
            <input type="text" name="phone" id="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}" required>
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- 🔐 Jelszó --}}
        <div class="mb-3">
            <label for="password" class="form-label">Jelszó</label>
            <input type="password" class="form-control" id="password"
                   name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required>
        </div>

        {{-- 🎭 Szerepkör választás – csak teszteléshez --}}
        <hr>
        <h5 class="mt-4">Szerepkör választás (teszteléshez)</h5>

        <div class="mb-3">
            <label for="role" class="form-label">Szerepkör</label>
            <select name="role" id="role" class="form-select" required>
                <option value="user">Felhasználó (alapértelmezett)</option>
                <option value="admin">Admin (teszt)</option>
                <option value="courier">Futár (teszt)</option>
            </select>
            <div class="alert alert-warning mt-2" role="alert">
                <strong>❗ Figyelem:</strong> Ez a mező csak <strong>tesztelési célból</strong> érhető el.<br>
                Éles rendszerben automatikusan „Felhasználó” szerepkört kap minden regisztráló.
            </div>
        </div>

        {{-- 🏡 Címadatok (opcionális) --}}
        <hr>
        <h5 class="mt-4">Cím kiszállításhoz (opcionális)</h5>

        <div class="mb-3">
            <label for="postal_code" class="form-label">Irányítószám</label>
            <input type="text" class="form-control" name="postal_code"
                   value="{{ old('postal_code') }}" maxlength="10">
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">Település</label>
            <input type="text" class="form-control" name="city"
                   value="{{ old('city') }}" maxlength="50">
        </div>

        <div class="mb-3">
            <label for="street_name" class="form-label">Közterület neve</label>
            <input type="text" class="form-control" name="street_name"
                   value="{{ old('street_name') }}" maxlength="100">
        </div>

        <div class="mb-3">
            <label for="street_number" class="form-label">Házszám</label>
            <input type="text" class="form-control" name="street_number"
                   value="{{ old('street_number') }}" maxlength="10">
        </div>

        {{-- ✅ Küldés gomb --}}
        <button type="submit" class="btn btn-success w-100">Fiók létrehozása</button>
    </form>
</div>
@endsection
