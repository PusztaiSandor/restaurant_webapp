@extends('layout')

@section('content')
  <div class="container py-4">
    {{-- Oldalcím --}}
    <h2>Profil szerkesztése</h2>

    {{-- Sikeres frissítés visszajelzése --}}
    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    {{-- Profiladatok frissítése --}}
    <form method="POST" action="{{ route('profile.update') }}">
      @csrf {{-- CSRF token --}}

      {{-- Név mező --}}
      <div class="mb-3">
        <label for="name">Név</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required
          minlength="2" maxlength="50" pattern="^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű.\- ]+$">
        <small class="form-text text-muted">
          Csak betűk, szóköz, pont és kötőjel. Minimum 2, maximum 50 karakter.
        </small>
      </div>

      {{-- Email mező --}}
      <div class="mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required
          minlength="5" maxlength="60" pattern="^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$">
        <small class="form-text text-muted">
          Betűk, számok, pont, kötőjel, aláhúzás és @ karakter engedélyezett. Minimum 5, maximum 60 karakter.
        </small>
      </div>

      {{-- Telefonszám mező --}}
      <div class="mb-3">
        <label for="phone">Telefonszám</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control"
          pattern="^\+36-(20|30|40|70)-\d{3}-\d{4}$">
        <small class="form-text text-muted">
          Formátum: +36-30-123-4567
        </small>
      </div>

      {{-- Szállítási cím szekció --}}
      <hr>
      <h5 class="mt-4">Szállítási cím</h5>

      {{-- Irányítószám --}}
      <div class="mb-3">
        <label for="postal_code">Irányítószám</label>
        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-control"
          maxlength="4" pattern="^\d{4}$">
        <small class="form-text text-muted">
          Pontosan 4 számjegy. Példa: 1139
        </small>
      </div>

      {{-- Település --}}
      <div class="mb-3">
        <label for="city">Település</label>
        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-control" maxlength="50"
          pattern="^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű -]{1,50}$">
        <small class="form-text text-muted">
          Csak betűk, szóköz és kötőjel. Példa: Budapest vagy Dunakeszi-Alag
        </small>
      </div>

      {{-- Közterület neve --}}
      <div class="mb-3">
        <label for="street_name">Közterület neve</label>
        <input type="text" name="street_name" value="{{ old('street_name', $user->street_name) }}" class="form-control"
          maxlength="100" pattern="^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 .-]{1,100}$">
        <small class="form-text text-muted">
          Betűk, számok, szóköz, pont és kötőjel. Példa: 10. kerület vagy 27. utca
        </small>
      </div>

      {{-- Házszám --}}
      <div class="mb-3">
        <label for="street_number">Házszám</label>
        <input type="text" name="street_number" value="{{ old('street_number', $user->street_number) }}"
          class="form-control" maxlength="10" pattern="^[0-9A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\/\-]{1,10}$">
        <small class="form-text text-muted">
          Számok, betűk, perjel és kötőjel. Példa: 15/A vagy 13–15
        </small>
      </div>

      {{-- Mentés gomb --}}
      <button type="submit" class="btn btn-primary">Mentés</button>
    </form>

    {{-- Jelszómódosítás szekció --}}
    <hr class="my-4">
    <h4>Jelszómódosítás</h4>

    {{-- Sikeres jelszófrissítés visszajelzése --}}
    @if (session('success_password'))
      <div class="alert alert-success">{{ session('success_password') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger mt-2">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Jelszó frissítő űrlap --}}
    <form method="POST" action="{{ route('profile.password') }}">
      @csrf

      {{-- Új jelszó --}}
      <div class="mb-3">
        <label for="password">Új jelszó</label>
        <input type="password" name="password" class="form-control" required minlength="8" maxlength="36"
          pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$">
        <small class="form-text text-muted">
          Legalább 8, legfeljebb 36 karakter. Tartalmazzon betűt és számot.
        </small>
      </div>

      {{-- Jelszó megerősítése --}}
      <div class="mb-3">
        <label for="password_confirmation">Új jelszó megerősítése</label>
        <input type="password" name="password_confirmation" class="form-control" required>
        <small class="form-text text-muted">
          Kérjük, ismételd meg az új jelszót pontosan.
        </small>
      </div>

      {{-- Jelszó frissítése gomb --}}
      <div class="mt-3">
        <button type="submit" class="btn btn-warning">Jelszó frissítése</button>
      </div>
    </form>
  @endsection
