@extends('layout')

@section('content')
  <div class="container py-4">

    <h2 class="mb-4 text-center">Étel szerkesztése</h2>

    @if ($errors->any())
      <div class="alert alert-danger text-center">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('admin.dishes.update', $dish) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="name" class="form-label">Étel neve:</label>
        <input type="text" name="name" id="name" class="form-control" required maxlength="100"
          value="{{ old('name', $dish->name) }}">
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Leírás:</label>
        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $dish->description) }}</textarea>
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">Kép fájlneve:</label>
        <input type="text" name="image" id="image" class="form-control" maxlength="255"
          value="{{ old('image', $dish->image ?? '') }}">
        <small class="text-muted">Pl.: sult_banan.jpg</small>
        @if ($dish->image)
          <small class="text-muted">Jelenlegi kép: {{ $dish->image }}</small>
        @endif
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="category" class="form-label">Kategória:</label>
          @php
            $categories = ['Étel', 'Ital', 'Desszert', 'Csomag', 'Fitness', 'Vegetáriánus', 'Nemzetközi'];
          @endphp
          <select name="category" id="category" class="form-select" required>
            <option value="">– Válassz kategóriát –</option>
            @foreach ($categories as $cat)
              <option value="{{ $cat }}" {{ old('category', $dish->category) === $cat ? 'selected' : '' }}>
                {{ $cat }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label for="type" class="form-label">Típus:</label>
          @php
            $types = [
                'Leves',
                'Egytálétel',
                'Húsétel',
                'Hamburger',
                'Pizza',
                'Zöldség',
                'Saláta',
                'Savanyúság',
                'Gyümölcs',
                'Köret',
                'Előétel',
                'Street_food',
                'Üdítő',
                'Kávé',
                'Tea',
                'Bor',
                'Sör',
                'Ásványvíz',
                'Koktél',
                'Gyerekital',
                'Sütemény',
                'Fagylalt',
            ];
          @endphp
          <select name="type" id="type" class="form-select" required>
            <option value="">– Válassz típust –</option>
            @foreach ($types as $type)
              <option value="{{ $type }}" {{ old('type', $dish->type) === $type ? 'selected' : '' }}>
                {{ $type }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label for="gross_price" class="form-label">Bruttó alapár (Ft):</label>
          <input type="number" name="gross_price" id="gross_price" class="form-control" required min="0"
            step="1" value="{{ old('gross_price', $dish->gross_price) }}">
        </div>
        <div class="col-md-4">
          <label for="tax_percent" class="form-label">ÁFA (%):</label>
          <input type="number" name="tax_percent" id="tax_percent" class="form-control" min="0" max="100"
            step="0.01" value="{{ old('tax_percent', $dish->tax_percent) }}">
        </div>

      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label d-block">Akciós:</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="on_sale" value="1"
              {{ old('on_sale', $dish->on_sale) == '1' ? 'checked' : '' }}>
            <label class="form-check-label">Igen</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="on_sale" value="0"
              {{ old('on_sale', $dish->on_sale) == '0' ? 'checked' : '' }}>
            <label class="form-check-label">Nem</label>
          </div>
        </div>

        <div class="col-md-8">
          <label for="discount_percent" class="form-label">Akciós kedvezmény (%):</label>
          <input type="number" name="discount_percent" id="discount_percent" class="form-control" min="0"
            max="100" step="0.01" value="{{ old('discount_percent', $dish->discount_percent) }}">
        </div>
      </div>


      <h5 class="mt-4">Méretprofil módosítása</h5>
      @php
        $sizeOptions = old('size_options', $dish->size_options ?? []);
      @endphp
      <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
          <tr>
            <th>Méret neve</th>
            <th>Mértékegység</th>
            <th>Mennyiség</th>
            <th>Szorzó</th>
          </tr>
        </thead>
        <tbody>
          @php
            $labels = array_keys($sizeOptions);
          @endphp
          @for ($i = 0; $i < max(3, count($labels)); $i++)
            @php
              $label = $labels[$i] ?? '';
              $value = $sizeOptions[$label] ?? [];
            @endphp
            <tr>
              <td><input type="text" name="size_labels[]" class="form-control text-center"
                  value="{{ old('size_labels.' . $i, $label) }}"></td>
              <td><input type="text" name="size_units[]" class="form-control text-center"
                  value="{{ old('size_units.' . $i, $value['unit'] ?? '') }}" placeholder="pl. cm, dl">
              </td>
              <td><input type="number" name="size_amounts[]" class="form-control text-center" min="0"
                  step="0.01" value="{{ old('size_amounts.' . $i, $value['amount'] ?? '') }}"></td>
              <td><input type="number" name="size_multipliers[]" class="form-control text-center" step="0.01"
                  value="{{ old('size_multipliers.' . $i, $value['multiplier'] ?? 1.0) }}"></td>
            </tr>
          @endfor
        </tbody>
      </table>
      <small class="text-muted">(Kicsi, Normál, Nagy) A nem kitöltött sorok figyelmen kívül lesznek hagyva
        mentéskor.</small>


      <div class="mb-3">
        <label for="base_ingredients" class="form-label">Alapösszetevők (vesszővel):</label>
        <input type="text" name="base_ingredients" id="base_ingredients" class="form-control"
          value="{{ old('base_ingredients', is_array($dish->base_ingredients) ? implode(',', $dish->base_ingredients) : $dish->base_ingredients) }}">
      </div>

      <div class="mb-3">
        <label for="extra_ingredients" class="form-label">Extra hozzávalók (vesszővel):</label>
        <input type="text" name="extra_ingredients" id="extra_ingredients" class="form-control"
          value="{{ old('extra_ingredients', is_array($dish->extra_ingredients) ? implode(',', $dish->extra_ingredients) : $dish->extra_ingredients) }}">
      </div>

      <div class="mb-3">
        <label for="ingredient_modifiers" class="form-label">Ármódosítók (JSON):</label>
        <textarea name="ingredient_modifiers" id="ingredient_modifiers" class="form-control" rows="2">{{ old('ingredient_modifiers', is_array($dish->ingredient_modifiers) ? json_encode($dish->ingredient_modifiers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $dish->ingredient_modifiers) }}</textarea>
        <small class="text-muted">Pl.: {"sajt":200,"bacon":300}</small>
      </div>

      <div class="mb-3">
        <label for="allergens" class="form-label">Allergének (vesszővel):</label>
        <input type="text" name="allergens" id="allergens" class="form-control"
          value="{{ old('allergens', is_array($dish->allergens) ? implode(',', $dish->allergens) : $dish->allergens) }}">
      </div>


      <div class="row mb-3">
        <div class="col-md-4">
          <label for="calories" class="form-label">Kalóriatartalom (kcal):</label>
          <input type="number" name="calories" id="calories" class="form-control" min="0" step="1"
            value="{{ old('calories', $dish->calories) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label d-block">Vegetáriánus:</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vegetarian" value="1"
              {{ old('vegetarian', $dish->vegetarian) == '1' ? 'checked' : '' }}>
            <label class="form-check-label">Igen</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="vegetarian" value="0"
              {{ old('vegetarian', $dish->vegetarian) == '0' ? 'checked' : '' }}>
            <label class="form-check-label">Nem</label>
          </div>
        </div>
        <div class="col-md-4">
          <label for="stock" class="form-label">Készlet (db):</label>
          <input type="number" name="stock" id="stock" class="form-control" min="0" step="1"
            required value="{{ old('stock', $dish->stock) }}">
        </div>
      </div>


      <div class="mb-3">
        <label class="form-label d-block">Aktív:</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="active" id="active_yes" value="1"
            {{ old('active', $dish->active) == '1' ? 'checked' : '' }}>
          <label class="form-check-label" for="active_yes">Igen</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="active" id="active_no" value="0"
            {{ old('active', $dish->active) == '0' ? 'checked' : '' }}>
          <label class="form-check-label" for="active_no">Nem</label>
        </div>
      </div>


      <button type="submit" class="btn btn-primary w-100">Változások mentése</button>

      <div class="text-center mt-3">
        <a href="{{ route('admin.dishes.index') }}" class="btn btn-outline-secondary">
          Vissza az étlaphoz
        </a>
      </div>
    </form>
  </div>
@endsection
