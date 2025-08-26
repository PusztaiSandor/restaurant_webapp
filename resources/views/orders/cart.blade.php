@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Kosár tartalma</h2>

    {{-- Visszajelzések --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="text-start mb-3">
        <a href="{{ route('menu') }}" class="btn btn-secondary">
            ← Vissza az étlapra
        </a>
    </div>

    @if (count($cart) === 0)
        <p class="text-center">A kosár üres.</p>
    @else
        <ul class="list-group mb-4">
            @foreach ($cart as $key => $item)
                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div class="mb-2 mb-md-0">
                        <strong>{{ $item['name'] }}</strong><br>

                        @if (!empty($item['size']))
                            <small class="text-muted">Méret: {{ ucfirst($item['size']) }}</small><br>
                        @endif

                        @if (!empty($item['extra_ingredients']))
                            <small class="d-block text-success">➕ Extrák: {{ implode(', ', $item['extra_ingredients']) }}</small>
                        @endif

                        @if (!empty($item['excluded_ingredients']))
                            <small class="d-block text-danger">❌ Kizárva: {{ implode(', ', $item['excluded_ingredients']) }}</small>
                        @endif

                        <small class="text-muted">Egységár: {{ number_format($item['price'], 0, ',', ' ') }} Ft</small><br>
                        <small class="d-block mt-1">Mennyiség: {{ $item['quantity'] }}</small>

                        {{-- Műveletek --}}
                        <form method="POST" action="{{ route('order.increase', ['key' => $key]) }}" class="d-inline me-2">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">+</button>
                        </form>

                        <form method="POST" action="{{ route('order.decrease', ['key' => $key]) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary">–</button>
                        </form>

                        <form method="POST" action="{{ route('order.remove', ['key' => $key]) }}" class="d-inline ms-2">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Törlés</button>
                        </form>
                    </div>

                    <span class="fw-bold">
                        {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} Ft
                    </span>
                </li>
            @endforeach
        </ul>

        {{-- Összesítés --}}
        @php
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0"><strong>Összesen:</strong> {{ number_format($total, 2, ',', ' ') }} Ft</p>

            {{-- Kosár ürítése --}}
            <form method="POST" action="{{ route('order.clear') }}">
                @csrf
                <button class="btn btn-outline-danger">Kosár ürítése</button>
            </form>
        </div>

        {{-- Rendelés véglegesítése --}}
        <div class="text-end">
            <a href="{{ route('order.checkout') }}" class="btn btn-primary">Rendelés véglegesítése</a>
        </div>
    @endif
</div>
@endsection
