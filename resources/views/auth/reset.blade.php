@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="text-center mb-4">Új jelszó megadása</h2>


    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('confirm-reset.post', ['email' => $email]) }}">
      @csrf

      <div class="mb-3">
        <label for="password" class="form-label">Új jelszó</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
          required>
        <small class="form-text text-muted">
          Legalább 8, legfeljebb 36 karakter. Csak kis- és nagybetűk, valamint számok. Példa: Teszt1234
        </small>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success w-100 mt-3">Jelszó módosítása</button>
    </form>
  </div>
@endsection
