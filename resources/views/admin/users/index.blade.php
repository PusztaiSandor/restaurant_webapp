@extends('layout')

@section('content')
<div class="container">
    {{-- Flash üzenetek megjelenítése: siker, hiba, információ --}}
    {{-- @foreach (['success', 'error', 'info'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }}">

                <i class="fa-solid fa-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-triangle' : 'info-circle') }} me-1"></i>
                {{ session($type) }}
            </div>
        @endif
    @endforeach --}}

    <h2>Felhasználók</h2>

    {{-- Felhasználók táblázata --}}
    <div class="table-responsive overflow-x-auto">
    <table class="table table-bordered align-middle w-100">
        <thead>
            <tr>
                <th>Név</th>
                <th>Email</th>
                <th>Szerepkör</th>
                <th>Állapot</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @php
                    // Ideiglenes hozzáférés ellenőrzése: belső email + jelszócsere szükséges
                    $hasTemporaryAccess = Str::endsWith($user->email, '@esszencia.local') && $user->must_change_password;
                @endphp

                <tr>
                    {{-- Felhasználó neve --}}
                    <td>{{ $user->name }}</td>

                    {{-- Email + ideiglenes hozzáférés badge és ikon --}}
                    <td>
                        {{ $user->email }}
                        @if($hasTemporaryAccess)
                            <span class="badge bg-warning text-dark ms-1">IDEIGLENES</span>
                            <i class="fa-solid fa-key text-danger ms-1" title="Ideiglenes hozzáférés"></i>
                        @endif
                    </td>

                    {{-- Szerepkör (pl. admin, user) --}}
                    <td>{{ ucfirst($user->role) }}</td>

                    {{-- Aktív / Inaktív állapot badge --}}
                    <td>
                        @if ($user->active)
                            <span class="badge bg-success">Aktív</span>
                        @else
                            <span class="badge bg-secondary">Inaktív</span>
                        @endif
                    </td>

                    {{-- Műveletek: szerkesztés + aktiválás/inaktiválás --}}
                    <td class="d-flex flex-wrap gap-1">
                        {{-- Szerkesztés gomb --}}
                        <a href="{{ route('admin.users.edit', $user->users_id) }}" class="btn btn-sm btn-primary">
                            Szerkesztés
                        </a>

                        {{-- Aktiválás/Inaktiválás gomb --}}
                        <form method="POST" action="{{ route('admin.users.toggle', $user->users_id) }}" class="d-inline ms-1">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm {{ $user->active ? 'btn-warning' : 'btn-success' }}">
                                {{ $user->active ? 'Inaktiválás' : 'Aktiválás' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
   </div>

    {{-- Új felhasználó létrehozása gomb --}}
    <a href="{{ route('admin.users.create') }}" class="btn btn-success mt-3">➕ Új felhasználó</a>
</div>
@endsection
