@extends('layout')

@section('content')
<div class="container">
    <h2>Új felhasználó létrehozása</h2>

    {{-- Felhasználó létrehozása űrlap --}}
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf {{-- Laravel CSRF token a biztonságos POST kéréshez --}}

        {{-- Név mező --}}
        <div class="mb-3">
            <label for="name">Név</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        {{-- Szerepkör kiválasztása --}}
        <div class="mb-3">
            <label for="role">Szerepkör</label>
            <select name="role" id="role" class="form-select" required>
                {{-- Elérhető szerepkörök --}}
                <option value="courier">Futár</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        {{-- Aktív állapot checkbox --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" checked>
            <label for="active" class="form-check-label">Aktív állapot</label>
        </div>

        {{-- Műveleti gombok: mentés és vissza --}}
        <button type="submit" class="btn btn-success">✅ Felhasználó létrehozása</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">⬅️ Vissza</a>
    </form>
</div>
@endsection
