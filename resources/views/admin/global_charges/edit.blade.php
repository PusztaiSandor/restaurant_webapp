@extends('layout')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Globális díj szerkesztése</h2>

    <form action="{{ route('global-charges.update', $charge->global_charges_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="charge_type" class="form-label">Díj típusa</label>
            <select name="charge_type" id="charge_type" class="form-select" required>
                <option value="delivery_fee" {{ $charge->charge_type === 'delivery_fee' ? 'selected' : '' }}>Szállítási díj</option>
                <option value="service_fee" {{ $charge->charge_type === 'service_fee' ? 'selected' : '' }}>Szervízdíj</option>
                <option value="order_discount" {{ $charge->charge_type === 'order_discount' ? 'selected' : '' }}>Rendelési kedvezmény</option>
                <option value="cutlery" {{ $charge->charge_type === 'cutlery' ? 'selected' : '' }}>Evőeszköz díj</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="delivery_method" class="form-label">Kiszállítás módja</label>
            <select name="delivery_method" id="delivery_method" class="form-select" required>
                <option value="delivery" {{ $charge->delivery_method === 'delivery' ? 'selected' : '' }}>Kiszállítás</option>
                <option value="dine-in" {{ $charge->delivery_method === 'dine-in' ? 'selected' : '' }}>Helyben fogyasztás</option>
                <option value="pickup" {{ $charge->delivery_method === 'pickup' ? 'selected' : '' }}>Személyes átvétel</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="value" class="form-label">Érték</label>
            <input type="number" step="0.01" name="value" id="value" class="form-control" value="{{ $charge->value }}" required>
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_percentage" value="0">
            <input type="checkbox" name="is_percentage" id="is_percentage" value="1" class="form-check-input" {{ $charge->is_percentage ? 'checked' : '' }}>
            <label for="is_percentage" class="form-check-label">Százalékos érték</label>
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" {{ $charge->is_active ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Aktív</label>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Leírás</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ $charge->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">💾 Mentés</button>
        <a href="{{ route('global-charges.index') }}" class="btn btn-secondary">⬅️ Vissza</a>
    </form>
</div>
@endsection
