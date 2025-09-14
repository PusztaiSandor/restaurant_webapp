@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="text-center mb-4">Jelszó módosítása</h2>

    <div class="alert alert-info text-center">
      A jelszómódosításhoz elküldtünk egy linket az e-mail címedre.
      <br>
      <small>(Jelszó módosításához kattints a Jelszómódosítás gombra)</small>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">

      <form method="GET" action="{{ route('confirm-reset', ['email' => $email]) }}">
        <button type="submit" class="btn btn-success w-100">Jelszómódosítás</button>
      </form>

      <form method="GET" action="{{ route('login') }}">
        <button type="submit" class="btn btn-secondary w-100">Mégsem</button>
      </form>
    </div>
  </div>
@endsection
