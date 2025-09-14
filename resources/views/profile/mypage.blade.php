@extends('layout')

@section('content')
  <div class="container py-4">

    @if (session('success_password'))
      <div class="alert alert-success">
        <i class="fa-solid fa-lock-open me-1"></i> {{ session('success_password') }}
      </div>
    @endif

    <h1 class="mb-4">Profilom</h1>

    <ul class="list-group">

      <li class="list-group-item">
        <strong>Név:</strong> {{ $user->name }}
      </li>

      <li class="list-group-item">
        <strong>Email:</strong> {{ $user->email }}
      </li>

      <li class="list-group-item">
        <strong>Telefonszám:</strong> {{ $user->phone ?? 'Nincs megadva' }}
      </li>

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

      <li class="list-group-item">
        <strong>Szerepkör:</strong> {{ $user->getRoleLabel() }}
      </li>
    </ul>

    <div class="mt-4">
      <a href="{{ route('profile.mypage.edit') }}" class="btn btn-sm btn-secondary">
        Profil szerkesztése
      </a>
    </div>
  </div>
@endsection
