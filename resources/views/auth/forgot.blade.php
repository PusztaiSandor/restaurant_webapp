@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="text-center mb-4">Elfelejtetted a jelszót?</h2>


    @if ($errors->has('email'))
      <div class="alert alert-danger text-center">
        {{ $errors->first('email') }}
      </div>
    @endif


    <form method="POST" action="{{ route('forgot-password.post') }}">
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
          <div class="invalid-feedback text-center">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary w-100">Jelszó módosítása</button>
    </form>
  </div>
@endsection
