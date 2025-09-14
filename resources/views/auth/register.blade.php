@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="text-center mb-4">Regisztráció</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" novalidate>
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">Név</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
          value="{{ old('name') }}" required minlength="2" maxlength="50" pattern="^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű. \-]+$"
          placeholder="Pl. Kiss-Kovács János">
        <small class="form-text text-muted">
          Közelező, csak betűk, szóköz, pont és kötőjel. Minimum 2, maximum 50 karakter.
        </small>
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail cím</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
          value="{{ old('email') }}" required minlength="5" maxlength="60"
          pattern="^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$" placeholder="Pl. kiss_auto@example.hu">
        <small class="form-text text-muted">
          Kötelező, betűk, számok, pont, kötőjel és aláhúzás engedélyezett. Minimum 5, maximum 60 karakter.
        </small>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Telefonszám</label>
        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
          value="{{ old('phone') }}" required placeholder="+36-30-123-4567">
        <small class="form-text text-muted">
          Kötelező, csak magyar mobilszám formátum: +36-20|30|40|70-123-4567.
        </small>
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Jelszó</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
          required>
        <small class="form-text text-muted">
          Kötelező, legalább 8, legfeljebb 36 karakter. Csak kis- és nagybetűk, valamint számok. Példa: Teszt123
        </small>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        <small class="form-text text-muted">
          Kötelező, jelszó egyezés szükséges.
        </small>
      </div>

      <hr>
      <h5 class="mt-4">Szállítási cím</h5>

      <div class="mb-3">
        <label for="postal_code" class="form-label">Irányítószám</label>
        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code"
          id="postal_code" value="{{ old('postal_code') }}" required maxlength="4" placeholder="Pl. 1139">
        <small class="form-text text-muted">
          Kötelező, magyar irányítószám, pontosan 4 számjegy. Példa: 1139
        </small>
        @error('postal_code')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="city" class="form-label">Település</label>
        <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" id="city"
          value="{{ old('city') }}" required maxlength="50" placeholder="Pl. Budapest">
        <small class="form-text text-muted">
          Kötelező, csak betűk, szóköz és kötőjel engedélyezett. Maximum 50 karakter. Példa: Dunakeszi-Alag
        </small>
        @error('city')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="street_name" class="form-label">Közterület neve</label>
        <input type="text" class="form-control @error('street_name') is-invalid @enderror" name="street_name"
          id="street_name" value="{{ old('street_name') }}" required maxlength="100" placeholder="Pl. 10. kerület">
        <small class="form-text text-muted">
          Kötelező, betűk, számok, szóköz, pont és kötőjel engedélyezett. Maximum 100 karakter. Példa: 27. utca
        </small>
        @error('street_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="street_number" class="form-label">Házszám</label>
        <input type="text" class="form-control @error('street_number') is-invalid @enderror" name="street_number"
          id="street_number" value="{{ old('street_number') }}" required maxlength="10" placeholder="Pl. 15/A">
        <small class="form-text text-muted">
          Kötelező, szám, betű, kötőjel és perjel engedélyezett. Maximum 10 karakter. Példa: 13–15 vagy 27/b
        </small>
        @error('street_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-success w-100">Fiók létrehozása</button>
    </form>
  </div>
@endsection
