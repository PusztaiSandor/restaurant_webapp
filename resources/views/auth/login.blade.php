@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="text-center mb-4">Belépés</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif


    <form method="POST" action="{{ route('login.submit') }}" novalidate>
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">E-mail cím</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
          required value="{{ old('email') }}" minlength="5" maxlength="60"
          pattern="^[A-Za-z0-9._\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$" placeholder="Pl. kiss_auto@example.hu">
        <small class="form-text text-muted">
          Betűk, számok, pont, kötőjel, aláhúzás és @ karakter engedélyezett. Minimum 5, maximum 60 karakter.
        </small>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Jelszó</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
          required>
        <small class="form-text text-muted">
          Legalább 8, legfeljebb 36 karakter. Csak kis- és nagybetűk, valamint számok. Példa: Teszt1234
        </small>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="text-end mt-2">
          <a href="{{ route('forgot-password') }}">Elfelejtettem a jelszavam</a>
        </div>
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label">Emlékezzen rám</label>
      </div>

      <button type="submit" class="btn btn-primary w-100">Belépés</button>
    </form>
  </div>
@endsection
