@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Asztalok kezelése</h2>

    <a href="{{ route('admin.tables.create') }}" class="btn btn-primary mb-3">Új asztal hozzáadása</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kód</th>
                <th>Elhelyezkedés</th>
                <th>Pozíció</th>
                <th>Kapacitás</th>
                <th>Foglalható</th>
                <th>Megjegyzés</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tables as $table)
                <tr>
                    <td>{{ $table->table_code }}</td>
                    <td>{{ ucfirst($table->location) }}</td>
                    <td>{{ ucfirst($table->position) }}</td>
                    <td>{{ $table->capacity }} fő</td>
                    <td>{{ $table->is_reservable ? 'Igen' : 'Nem' }}</td>
                    <td>{{ $table->notes }}</td>
                    <td>
                        <a href="{{ route('admin.tables.edit', $table->tables_id) }}" class="btn btn-sm btn-outline-secondary">Szerkesztés</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
