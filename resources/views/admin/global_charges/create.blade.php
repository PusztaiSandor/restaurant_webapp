@extends('layout')

@section('content')
  <div class="container py-4">
    <h2 class="mb-4">Új globális díj hozzáadása</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('global-charges.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="charge_type" class="form-label">Díj típusa</label>
        <select name="charge_type" id="charge_type" class="form-select" required>
          <option value="">-- Válassz --</option>
          <option value="delivery_fee">Szállítási díj</option>
          <option value="service_fee">Szervízdíj</option>
          <option value="order_discount">Rendelési kedvezmény</option>
          <option value="cutlery">Evőeszköz díj</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="delivery_method" class="form-label">Kiszállítás módja</label>
        <select name="delivery_method" id="delivery_method" class="form-select" required>
          <option value="">-- Válassz --</option>
          <option value="delivery">Kiszállítás</option>
          <option value="dine-in">Helyben fogyasztás</option>
          <option value="pickup">Személyes átvétel</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="value" class="form-label">Érték</label>
        <input type="number" step="0.01" name="value" id="value" class="form-control" required>
      </div>

      <div class="form-check mb-3">
        <input type="hidden" name="is_percentage" value="0">
        <input type="checkbox" name="is_percentage" id="is_percentage" value="1" class="form-check-input">
        <label for="is_percentage" class="form-check-label">Százalékos érték</label>
      </div>

      <div class="form-check mb-3">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" checked>
        <label for="is_active" class="form-check-label">Aktív</label>
      </div>

      <div class="form-check mb-3">
        <input type="hidden" name="is_optional" value="0">
        <input type="checkbox" name="is_optional" id="is_optional" value="1" class="form-check-input">
        <label for="is_optional" class="form-check-label">Választható</label>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Leírás</label>
        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
      </div>

      <button type="submit" class="btn btn-success">Mentés</button>
      <a href="{{ route('global-charges.index') }}" class="btn btn-secondary">Vissza a globális díjakhoz</a>
    </form>
  </div>
@endsection
