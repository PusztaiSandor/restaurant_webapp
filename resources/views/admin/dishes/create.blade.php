@extends('layout')

@section('content')
<div class="container py-4">
    {{-- 🧾 Oldalcím --}}
    <h2 class="mb-4 text-center">🍽️ Új étel felvétele</h2>

    {{-- ⚠️ Validációs hibák megjelenítése --}}
    @if ($errors->any())
        <div class="alert alert-danger text-center">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- 📝 Étel létrehozása űrlap --}}
    <form method="POST" action="{{ route('admin.dishes.store') }}">
        @csrf

        {{-- ➤ Alapinformációk --}}
        <div class="mb-3">
            <label for="name" class="form-label">Étel neve:</label>
            <input type="text" name="name" id="name" class="form-control" required maxlength="100" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Leírás:</label>
            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Kép fájlneve:</label>
            <input type="text" name="image" id="image" class="form-control" maxlength="255" value="{{ old('image') }}">
            <small class="text-muted">Pl.: sult_banan.jpg</small>
        </div>

        {{-- ➤ Kategória és típus --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="category" class="form-label">Kategória:</label>
                @php
                    $categories = ['Étel', 'Ital', 'Desszert', 'Csomag', 'Fitness', 'Vegetáriánus', 'Vegan', 'Gluten_free', 'Laktoze_free', 'Nemzetközi'];
                @endphp
                <select name="category" id="category" class="form-select" required>
                    <option value="">– Válassz kategóriát –</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="type" class="form-label">Típus:</label>
                @php
                    $types = ['Leves', 'Egy_tál_étel', 'Húsétel', 'Hamburger', 'Pizza', 'Zöldség', 'Saláta', 'Savanyúság', 'Gyümölcs', 'Köret', 'Előétel', 'Street_food', 'Üdítő', 'Kávé', 'Tea', 'Bor', 'Koktél', 'Gyerekital', 'Sütemény', 'Fagylalt', 'Pohár_krém'];
                @endphp
                <select name="type" id="type" class="form-select" required>
                    <option value="">– Válassz típust –</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ➤ Ár és akció --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="gross_price" class="form-label">Bruttó ár (Ft):</label>
                <input type="number" name="gross_price" id="gross_price" class="form-control" required min="0" step="0.01" value="{{ old('gross_price') }}">
            </div>
            <div class="col-md-4">
                <label for="tax_percent" class="form-label">ÁFA (%):</label>
                <input type="number" name="tax_percent" id="tax_percent" class="form-control" min="0" max="100" step="0.01" value="{{ old('tax_percent', '27.00') }}">
            </div>
            <div class="col-md-4">
                <label for="discount_percent" class="form-label">Akciós kedvezmény (%):</label>
                <input type="number" name="discount_percent" id="discount_percent" class="form-control" min="0" max="100" step="0.01" value="{{ old('discount_percent', '0.00') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Akciós:</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="on_sale" value="1" {{ old('on_sale') == '1' ? 'checked' : '' }}>
                <label class="form-check-label">Igen</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="on_sale" value="0" {{ old('on_sale', '0') == '0' ? 'checked' : '' }}>
                <label class="form-check-label">Nem</label>
            </div>
        </div>

        {{-- ➤ Méretprofil táblázat --}}
        <h5 class="mt-4">➕ Méretprofil beállítása</h5>
        @php
            $defaultSizes = ['Kicsi', 'Normál', 'Nagy'];
        @endphp
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>Méret neve</th>
                    <th>Mértékegység</th>
                    <th>Mennyiség</th>
                    <th>Szorzó</th>
                    <th>Ármódosító (Ft)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($defaultSizes as $index => $size)
                <tr>
                    <td><input type="text" name="size_labels[]" class="form-control text-center" value="{{ old('size_labels.' . $index, $size) }}"></td>
                    <td><input type="text" name="size_units[]" class="form-control text-center" placeholder="pl. cm, dl" value="{{ old('size_units.' . $index) }}"></td>
                    <td><input type="number" name="size_amounts[]" class="form-control text-center" min="0" step="0.01" value="{{ old('size_amounts.' . $index) }}"></td>
                    <td><input type="number" name="size_multipliers[]" class="form-control text-center" step="0.01" value="{{ old('size_multipliers.' . $index, 1.00) }}"></td>
                    <td><input type="number" name="size_modifiers[]" class="form-control text-center" step="1" value="{{ old('size_modifiers.' . $index, 0) }}"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <small class="text-muted">Add meg a méretvariációkat mértékegységgel, szorzóval és ármódosítással.</small>

        {{-- ➤ Összetevők --}}
        <div class="mb-3 mt-4">
            <label for="base_ingredients" class="form-label">Alapösszetevők (vesszővel):</label>
            <input type="text" name="base_ingredients" id="base_ingredients" class="form-control"
                value="{{ is_array(old('base_ingredients')) ? implode(',', old('base_ingredients')) : old('base_ingredients') }}">
        </div>

        <div class="mb-3">
            <label for="extra_ingredients" class="form-label">Extra hozzávalók (vesszővel):</label>
            <input type="text" name="extra_ingredients" id="extra_ingredients" class="form-control"
                value="{{ is_array(old('extra_ingredients')) ? implode(',', old('extra_ingredients')) : old('extra_ingredients') }}">
        </div>

        <div class="mb-3">
            <label for="ingredient_modifiers" class="form-label">Hozzávaló ármódosítók (JSON):</label>
            <textarea name="ingredient_modifiers" id="ingredient_modifiers" class="form-control" rows="2">{{ old('ingredient_modifiers') }}</textarea>
            <small class="text-muted">
                Példa: {"sajt":200,"bacon":300} – azaz a „sajt” +200 Ft, a „bacon” +300 Ft
            </small>
        </div>

       {{-- ➤ Egyéb jellemzők --}}
        <div class="row mb-3">
            {{-- 🔥 Kalóriatartalom --}}
            <div class="col-md-4">
                <label for="calories" class="form-label">Kalóriatartalom (kcal):</label>
                <input type="number" name="calories" id="calories" class="form-control" min="0" step="1" value="{{ old('calories') }}">
            </div>

            {{-- 🌱 Vegetáriánus jelölés --}}
            <div class="col-md-4">
                <label class="form-label d-block">Vegetáriánus:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vegetarian" value="1" {{ old('vegetarian') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label">Igen</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vegetarian" value="0" {{ old('vegetarian', '0') == '0' ? 'checked' : '' }}>
                    <label class="form-check-label">Nem</label>
                </div>
            </div>

            {{-- 📦 Készlet --}}
            <div class="col-md-4">
                <label for="stock" class="form-label">Készlet (db):</label>
                <input type="number" name="stock" id="stock" class="form-control" min="0" step="1" required value="{{ old('stock') }}">
            </div>
        </div>

        {{-- ➤ Allergének --}}
        <div class="mb-3">
            <label for="allergens" class="form-label">Allergének (vesszővel):</label>
            <input type="text" name="allergens" id="allergens" class="form-control"
                value="{{ is_array(old('allergens')) ? implode(',', old('allergens')) : old('allergens') }}">
        </div>

        {{-- ➤ Aktív állapot --}}
        <div class="mb-3">
            <label class="form-label d-block">Aktív:</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="active" id="active_yes" value="1" {{ old('active', '1') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="active_yes">Igen</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="active" id="active_no" value="0" {{ old('active') == '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="active_no">Nem</label>
            </div>
        </div>

        {{-- ➤ Mentés gomb --}}
        <button type="submit" class="btn btn-success w-100">➕ Étel felvétele</button>

        {{-- ⬅️ Vissza az étlaphoz --}}
        <div class="text-center mt-3">
            <a href="{{ route('admin.dishes.index') }}" class="btn btn-outline-secondary">
                ⬅️ Vissza az étlaphoz
            </a>
        </div>
    </form>
</div>
@endsection
