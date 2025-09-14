@extends('layout')

@section('content')
  <div class="container py-4">
    <h2 class="mb-4">Globális díjak</h2>

    <a href="{{ route('global-charges.create') }}" class="btn btn-primary mb-3">
      <i class="fa-solid fa-plus me-1"></i> Új díj hozzáadása
    </a>

    <div class="table-responsive overflow-x-auto">
      <table class="table table-bordered table-hover w-100">
        <thead class="table-dark">
          <tr>
            <th>Típus</th>
            <th>Módszer</th>
            <th>Érték</th>
            <th>Százalékos?</th>
            <th>Aktív?</th>
            <th>Választható?</th>
            <th>Leírás</th>
            <th>Műveletek</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($charges as $charge)
            <tr>
              <td>{{ $charge->charge_type_label }}</td>
              <td>{{ $charge->delivery_method_label }}</td>
              <td>{{ $charge->value }} {{ $charge->is_percentage ? '%' : 'Ft' }}</td>
              <td>{{ $charge->is_percentage ? 'Igen' : 'Nem' }}</td>
              <td>{{ $charge->is_active ? 'Igen' : 'Nem' }}</td>
              <td>{{ $charge->is_optional ? 'Igen' : 'Nem' }}</td>
              <td>{{ $charge->description }}</td>
              <td class="d-flex gap-2">
                <a href="{{ route('global-charges.edit', $charge->global_charges_id) }}" class="btn btn-sm btn-warning">
                  Szerkesztés
                </a>

              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">Nincs még rögzített díj.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
@endsection
