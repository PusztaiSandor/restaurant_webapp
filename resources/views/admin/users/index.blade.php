@extends('layout')

@section('content')
  <div class="container">

    <h2>Felhasználók</h2>

    <div class="table-responsive overflow-x-auto">
      <table class="table table-bordered align-middle w-100">
        <thead>
          <tr>
            <th>Név</th>
            <th>Email</th>
            <th>Szerepkör</th>
            <th>Állapot</th>
            <th>Műveletek</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            @php
              // Ideiglenes hozzáférés ellenőrzése: belső email + jelszócsere szükséges
              $hasTemporaryAccess = Str::endsWith($user->email, '@esszencia.local') && $user->must_change_password;
            @endphp

            <tr>

              <td>{{ $user->name }}</td>

              <td>
                {{ $user->email }}
                @if ($hasTemporaryAccess)
                  <span class="badge bg-warning text-dark ms-1">IDEIGLENES</span>
                @endif
              </td>

              <td>{{ $user->getRoleLabel() }}</td>

              <td>
                @if ($user->active)
                  <span class="badge bg-success">Aktív</span>
                @else
                  <span class="badge bg-secondary">Inaktív</span>
                @endif
              </td>

              <td class="d-flex flex-wrap gap-1">
                <a href="{{ route('admin.users.edit', $user->users_id) }}" class="btn btn-sm btn-primary">
                  Szerkesztés
                </a>
                <form method="POST" action="{{ route('admin.users.toggle', $user->users_id) }}" class="d-inline ms-1">
                  @csrf
                  @method('PATCH')
                  <button class="btn btn-sm {{ $user->active ? 'btn-warning' : 'btn-success' }}">
                    {{ $user->active ? 'Inaktiválás' : 'Aktiválás' }}
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <a href="{{ route('admin.users.create') }}" class="btn btn-success mt-3">Új felhasználó</a>
  </div>
@endsection
