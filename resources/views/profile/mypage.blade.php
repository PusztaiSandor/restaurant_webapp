@extends('layout')

@section('content')
<div class="container py-4">
    {{-- Sikeres műveletek visszajelzése --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('success_password'))
        <div class="alert alert-success">
            <i class="fa-solid fa-lock-open me-1"></i> {{ session('success_password') }}
        </div>
    @endif

    {{-- Felhasználói profil cím --}}
    <h1 class="mb-4">Profilom</h1>

    {{-- Felhasználói adatok listája --}}
    <ul class="list-group">
        {{-- Név megjelenítése --}}
        <li class="list-group-item">
            <strong>Név:</strong> {{ $user->name }}
        </li>

        {{-- Email megjelenítése --}}
        <li class="list-group-item">
            <strong>Email:</strong> {{ $user->email }}
        </li>

        {{-- Telefonszám megjelenítése --}}
        <li class="list-group-item">
            <strong>Telefonszám:</strong> {{ $user->phone ?? 'Nincs megadva' }}
        </li>

        {{-- Cím adatok --}}
        <li class="list-group-item">
            <strong>Irányítószám:</strong> {{ $user->postal_code ?? 'Nincs megadva' }}
        </li>
        <li class="list-group-item">
            <strong>Város:</strong> {{ $user->city ?? 'Nincs megadva' }}
        </li>
        <li class="list-group-item">
            <strong>Utca:</strong> {{ $user->street_name ?? 'Nincs megadva' }}
        </li>
        <li class="list-group-item">
            <strong>Házszám:</strong> {{ $user->street_number ?? 'Nincs megadva' }}
        </li>

        {{-- Szerepkör megjelenítése --}}
        <li class="list-group-item">
            <strong>Szerepkör:</strong> {{ $user->role }}
        </li>
    </ul>

    {{-- Profil szerkesztése gomb --}}
<div class="mt-4">
    <a href="{{ route('profile.mypage.edit') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-user-pen me-1"></i> Profil szerkesztése
    </a>
</div>
</div>
@endsection
