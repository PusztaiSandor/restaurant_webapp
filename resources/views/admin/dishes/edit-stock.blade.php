@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="mb-4 text-center">📦 Készlet módosítása</h2>


    @if ($errors->any())
      <div class="alert alert-danger text-center">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif


    <div class="card mx-auto" style="max-width: 500px;">
      <div class="card-body">

        <h5 class="card-title text-center">„{{ $dish->name }}”</h5>


        <p class="text-center text-muted">
          Jelenlegi készlet: <strong>{{ $dish->stock }}</strong> db
        </p>


        <form method="POST" action="{{ route('admin.dishes.stock.update', $dish->dishes_id) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="stock" class="form-label">Új készlet (db):</label>
            <input type="number" name="stock" id="stock" min="0" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Készlet mentése</button>
        </form>

        <div class="text-center mt-3">
          <a href="{{ route('admin.dishes.index') }}" class="btn btn-outline-secondary">
            Vissza az étlaphoz
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
