@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="text-center mb-4">Admin Étlap</h2>

    @if (auth()->user()?->role === 'admin')
      <div class="mb-4 text-end">
        <a href="{{ route('admin.dishes.create') }}" class="btn btn-outline-success">
          Új étel hozzáadása
        </a>
      </div>
    @endif

    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>Étel neve</th>
          <th>Alapár</th>
          <th>Készlet</th>
          @if (auth()->user()?->role === 'admin')
            <th>Művelet</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @forelse($activeDishes as $dish)
          <tr>
            <td>{{ $dish->name }}</td>
            <td>{{ number_format($dish->gross_price, 0, '', ' ') }} Ft</td>
            <td>{{ $dish->stock }} db</td>

            @if (auth()->user()?->role === 'admin')
              <td>

                <a href="{{ route('admin.dishes.edit', $dish->dishes_id) }}" class="btn btn-sm btn-outline-secondary me-1">
                  Étel szerkesztése
                </a>


                <a href="{{ route('admin.dishes.editStock', $dish->dishes_id) }}" class="btn btn-sm btn-outline-primary">
                  Készlet módosítása
                </a>

                <form method="POST" action="{{ route('admin.dishes.deactivate', $dish->dishes_id) }}" class="d-inline"
                  onsubmit="return confirm('Biztosan archiválni szeretnéd ezt az ételt?');">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-sm btn-outline-danger">
                    Archiválás
                  </button>
                </form>
              </td>
            @endif
          </tr>
        @empty

          <tr>
            <td colspan="{{ auth()->user()?->role === 'admin' ? 4 : 3 }}" class="text-center">
              Nincs elérhető étel.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>


    <h3 class="mt-5 mb-3 text-center">Archivált ételek</h3>

    <table class="table table-bordered table-hover table-secondary">
      <thead class="table-light">
        <tr>
          <th>Étel neve</th>
          <th>Alapár</th>
          <th>Készlet</th>
          <th>Művelet</th>
        </tr>
      </thead>
      <tbody>
        @forelse($archivedDishes as $dish)
          <tr>
            <td>{{ $dish->name }}</td>
            <td>{{ number_format($dish->gross_price, 0, '', ' ') }} Ft</td>
            <td>{{ $dish->stock }} db</td>
            <td>

              <form method="POST" action="{{ route('admin.dishes.activate', $dish->dishes_id) }}" class="d-inline"
                onsubmit="return confirm('Biztosan visszaállítod ezt az ételt az étlapra?');">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-sm btn-outline-success">
                  Visszaállítás
                </button>
              </form>
            </td>
          </tr>
        @empty

          <tr>
            <td colspan="4" class="text-center">Nincs archivált étel.</td>
          </tr>
        @endforelse
      </tbody>
    </table>


    <div class="text-center mt-4">
      <a href="{{ url('/') }}" class="btn btn-outline-secondary">Vissza a főoldalra</a>
    </div>
  </div>
@endsection
