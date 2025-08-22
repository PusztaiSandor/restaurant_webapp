@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Új asztal létrehozása</h2>

    <form method="POST" action="{{ route('admin.tables.store') }}">
        @csrf

        <div class="mb-3">
            <label for="location" class="form-label">Elhelyezkedés</label>
            <select name="location" id="location" class="form-select" required>
                <option value="beltér">Beltér</option>
                <option value="terasz">Terasz</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Pozíció</label>
            <select name="position" id="position" class="form-select" required>
                <option value="bal">Bal</option>
                <option value="közép">Közép</option>
                <option value="jobb">Jobb</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="capacity" class="form-label">Kapacitás (fő)</label>
            <input type="number" name="capacity" id="capacity" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label for="is_reservable" class="form-label">Foglalható</label>
            <select name="is_reservable" id="is_reservable" class="form-select">
                <option value="1">Igen</option>
                <option value="0">Nem</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Megjegyzés</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Mentés</button>
    </form>
</div>
@endsection
