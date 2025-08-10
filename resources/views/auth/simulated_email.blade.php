@extends('layout') {{-- 🌐 Alap layout betöltése --}}

@section('content')
<div class="container py-4">
    {{-- 🧾 Oldalcím --}}
    <h2 class="text-center mb-4">Jelszó visszaállítása</h2>

    {{-- ℹ️ Információs üzenet: szimulált e-mail küldés --}}
    <div class="alert alert-info text-center">
        A jelszómódosításhoz elküldtünk egy linket az e-mail címedre. 📩
        <br>
        <small>(Ez most csak szimuláció – nincs valódi levél.)</small>
    </div>

    {{-- 🔗 Műveleti gombok: jelszómódosítás vagy visszalépés --}}
    <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
        {{-- ✅ Jelszómódosítás gomb – átirányítás a reset formra --}}
        <form method="GET" action="{{ route('confirm-reset', ['email' => $email]) }}">
            <button type="submit" class="btn btn-success w-100">Jelszómódosítás</button>
        </form>

        {{-- ❌ Mégsem gomb – visszalépés a bejelentkezéshez --}}
        <form method="GET" action="{{ route('login') }}">
            <button type="submit" class="btn btn-secondary w-100">Mégsem</button>
        </form>
    </div>
</div>
@endsection
