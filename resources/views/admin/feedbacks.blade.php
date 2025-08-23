@extends('layout')

@section('content')
<section class="container mx-auto px-4 py-12">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Felhasználói visszajelzések</h1>

  <form method="GET" action="{{ route('admin.feedbacks') }}" class="mb-6 flex flex-wrap gap-4 items-center">
  <div>
    <label for="type" class="font-semibold text-gray-700 mr-2">Típus:</label>
    <select name="type" id="type" class="form-select">
      <option value="">Mind</option>
      <option value="message" {{ request('type') === 'message' ? 'selected' : '' }}>Üzenetek</option>
      <option value="rating" {{ request('type') === 'rating' ? 'selected' : '' }}>Értékelések</option>
    </select>
  </div>

  <div>
    <label for="sort" class="font-semibold text-gray-700 mr-2">Rendezés:</label>
    <select name="sort" id="sort" class="form-select">
      <option value="">Alapértelmezett</option>
      <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>Dátum ↓</option>
      <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Dátum ↑</option>
      <option value="rating_desc" {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>Értékelés ↓</option>
      <option value="rating_asc" {{ request('sort') === 'rating_asc' ? 'selected' : '' }}>Értékelés ↑</option>
    </select>
  </div>

  <button type="submit" class="btn btn-sm btn-dark">Szűrés</button>
</form>

  <table class="table-auto w-full text-left bg-white shadow-md rounded">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2">Felhasználó</th>
        <th class="px-4 py-2">Típus</th>
        <th class="px-4 py-2">Értékelés</th>
        <th class="px-4 py-2">Tárgy</th>
        <th class="px-4 py-2">Tartalom</th>
        <th class="px-4 py-2">Dátum</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($feedbacks as $fb)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $fb->user->name ?? 'Ismeretlen' }}</td>
          <td class="px-4 py-2">{{ $fb->type === 'rating' ? 'Értékelés' : 'Üzenet' }}</td>
          <td class="px-4 py-2">{{ $fb->rating ?? '–' }}</td>
          <td class="px-4 py-2">{{ $fb->subject ?? '–' }}</td>
          <td class="px-4 py-2">{{ $fb->content }}</td>
          <td class="px-4 py-2">{{ $fb->created_at->format('Y.m.d H:i') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-4 py-4 text-center text-gray-500">Nincs visszajelzés.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</section>
@endsection
