@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Asztalfoglalás – Rendelés #{{ $order->orders_id }}</h2>

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <input type="hidden" name="orders_id" value="{{ $order->orders_id }}">

        <div class="mb-3">
            <label for="seats" class="form-label">Hány főre szeretnél foglalni?</label>
            <input type="number" name="seats" id="seats" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Választható asztalok:</label>
            <div class="row">
                @foreach ($tables as $table)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="table_code[]" value="{{ $table->table_code }}" id="table_{{ $table->tables_id }}">
                            <label class="form-check-label" for="table_{{ $table->tables_id }}">
                                {{ strtoupper($table->table_code) }} – {{ ucfirst($table->location) }}, {{ ucfirst($table->position) }} ({{ $table->capacity }} fő)
                                @if ($table->notes)
                                    <br><small class="text-muted">{{ $table->notes }}</small>
                                @endif
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-success">Foglalás mentése</button>
    </form>
</div>
@endsection
