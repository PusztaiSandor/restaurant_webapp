@extends('layout')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="mb-4 text-center">Étlap</h2>

    {{-- 🔍 Szűrési lehetőségek --}}
    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <label for="category" class="form-label">Kategória:</label>
            <select name="category" id="category" class="form-select">
                <option value="">-- Mind --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="type" class="form-label">Típus:</label>
            <select name="type" id="type" class="form-select">
                <option value="">-- Mind --</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="sort" class="form-label">Rendezés:</label>
            <select name="sort" id="sort" class="form-select">
                <option value="">-- Alapértelmezett --</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Ár szerint növekvő</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Ár szerint csökkenő</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Szűrés</button>
        </div>
    </form>

    <a href="{{ route('menu.pdf') }}"
       class="btn btn-outline-primary mb-4"
       title="Nyomtatható étlap letöltése PDF-ben"
       target="_blank">
       <i class="bi bi-file-earmark-pdf"></i> Étlap PDF
    </a>

    <div class="row">
     @forelse ($dishes as $dish)
            @php
    // Alapértelmezett méret kiválasztása
    $sizeOptions = $dish->size_options ?? [];
    $defaultSize = isset($sizeOptions['Normál']) ? 'Normál' : array_key_first($sizeOptions);

    // Végső ár (extrák nélkül, kedvezménnyel)
    $finalPrice = $dish->getDiscountedSizePrice($defaultSize);

    // 🔧 Méretarányos árak előkészítése JS-hez (extrák nélkül)
    $priceMap = [];
    foreach ($sizeOptions as $label => $option) {
        $priceMap[$label] = $dish->getDiscountedSizePrice($label);
    }
@endphp

{{-- 💾 Méretarányos árak JS-hez --}}
<script>
    window.priceMap_{{ $dish->dishes_id }} = @json($priceMap);
    window.sizeOptions_{{ $dish->dishes_id }} = @json($dish->size_options);
</script>

<script>
window.dishData_{{ $dish->dishes_id }} = {
    basePrice: {{ $dish->gross_price }},
    discountPercent: {{ $dish->on_sale ? $dish->discount_percent : 0 }},
    sizeOptions: @json($dish->size_options)
};
</script>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm d-flex flex-column" id="dish_{{ $dish->dishes_id }}">
                    @if ($dish->image)
                        <img src="{{ asset('assets/images/termekek/' . $dish->image) }}" class="card-img-top"
                             alt="{{ $dish->name }}">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $dish->name }}</h5>

                        {{-- 💰 Ár megjelenítése --}}
<div class="mb-3">
    @php
    // A lenyílóban használt alapértelmezett méret
    $defaultSize = isset($dish->size_options['Normál']) ? 'Normál' : array_key_first($dish->size_options);
    $finalPrice = $dish->getDiscountedSizePrice($defaultSize);
    $originalPrice = $dish->getOriginalPrice($defaultSize);
@endphp

    <strong>Ár:</strong><br>

<span id="price_{{ $dish->dishes_id }}" class="fw-bold text-dark">
    {{ number_format($finalPrice, 0, ',', ' ') }} Ft
</span><br>

    {{-- 🎯 Kedvezményes ár megjelenítése, ha van akció --}}
    @if ($dish->on_sale && $dish->discount_percent > 0)
    <small id="discount_{{ $dish->dishes_id }}" class="text-muted d-block mt-1">
        Eredeti ár: <del id="original_{{ $dish->dishes_id }}">
            {{ number_format($originalPrice, 0, ',', ' ') }} Ft
        </del> • Kedvezmény: –{{ $dish->discount_percent }}%
    </small>
@endif
</div>

                        <p class="card-text"><strong>Elérhető:</strong> {{ $dish->stock }} db</p>
                        <div class="mt-auto">
                            @if ($dish->stock > 0)
                                <a href="{{ route('dishes.show', ['dish' => $dish->dishes_id]) }}" class="btn btn-sm btn-success w-100 mb-2">Részletek és rendelés</a>

                                <form method="POST" action="{{ route('cart.quickAdd', $dish->dishes_id) }}">
                                    @csrf

                                    @if (!empty($sizeOptions))
                                        <div class="mb-2">
                                            <label for="size_{{ $dish->dishes_id }}" class="form-label small">Méret:</label>
                                            <select name="size" id="size_{{ $dish->dishes_id }}" class="form-select form-select-sm">
    @foreach ($sizeOptions as $label => $option)
        @php
            $price = $dish->getDiscountedSizePrice($label);
        @endphp
        <option value="{{ $label }}" {{ $label === $defaultSize ? 'selected' : '' }}>
            {{ $label }} ({{ number_format($price, 0, ',', ' ') }} Ft)
        </option>
    @endforeach
</select>
                                        </div>
                                    @endif

                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                        ➕ Kosárba
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-secondary w-100 mt-2" disabled title="Jelenleg nincs készleten">
                                    Elfogyott
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Nincs még elérhető étel az étlapon.</p>
        @endforelse
    </div>
</div>

{{-- ⚙️ JavaScript: dinamikus árfrissítés méretváltáskor --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const formatPrice = val => Math.round(val).toLocaleString('hu-HU') + ' Ft';

    @foreach($dishes as $dish)
        const dishId = {{ $dish->dishes_id }};
        const select = document.getElementById(`size_${dishId}`);
        const priceDisplay = document.getElementById(`price_${dishId}`);
        const originalDisplay = document.getElementById(`original_${dishId}`);
        const discountDisplay = document.getElementById(`discount_${dishId}`);
        const data = window[`dishData_${dishId}`];

        const calculatePrice = () => {
            const selectedSize = select?.value || 'Normál';
            const sizeData = data.sizeOptions[selectedSize] || { multiplier: 1.0 };

            const multiplier = parseFloat(sizeData.multiplier || 1.0);

            const basePrice = parseFloat(data.basePrice);
            const discountPercent = parseFloat(data.discountPercent);

            // 💰 Alapár + szorzó + módosító
            let gross = basePrice * multiplier;

            // 🎯 Kedvezmény levonása
            let discount = discountPercent > 0 ? gross * (discountPercent / 100) : 0;
            let finalPrice = gross - discount;

            // 💡 Ár megjelenítése
            if (priceDisplay) priceDisplay.textContent = formatPrice(finalPrice);

            // 🧾 Eredeti ár megjelenítése, ha van kedvezmény
            if (discountPercent > 0 && originalDisplay && discountDisplay) {
                originalDisplay.textContent = formatPrice(gross);
                discountDisplay.innerHTML = `Eredeti ár: <del id="original_${dishId}">${formatPrice(gross)}</del> • Kedvezmény: –${discountPercent}%`;
            } else if (discountDisplay) {
                discountDisplay.textContent = '';
            }
        };

        select?.addEventListener('change', calculatePrice);
        calculatePrice();
    @endforeach
});
</script>

@endsection



