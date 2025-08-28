@extends('layout')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">{{ $dish->name }}</h2>

    <form method="POST" action="{{ route('order.add', ['dish' => $dish->dishes_id]) }}">
        @csrf

        <div class="row g-4">
            {{-- Bal oldal --}}
            <div class="col-md-5">
                @if ($dish->image)
                    <img src="{{ asset('assets/images/termekek/' . $dish->image) }}" class="img-fluid rounded shadow-sm mb-3" alt="{{ $dish->name }}">
                @endif

                <p>{{ $dish->description }}</p>

                <ul class="list-unstyled mb-3">
                    @if($dish->base_ingredients)
                        <li><strong>🧂 Összetevők:</strong> {{ implode(', ', $dish->base_ingredients) }}</li>
                    @endif
                    @if($dish->calories)
                        <li><strong>🔥 Kalóriatartalom:</strong> {{ $dish->calories }} kcal</li>
                    @endif
                    <li><strong>🌱 Vegetáriánus:</strong> {{ $dish->vegetarian ? 'Igen' : 'Nem' }}</li>
                    @if($dish->allergens)
    <li><strong>⚠️ Allergének:</strong> {{ implode(', ', $dish->allergens) }}</li>
@endif
                    <li><strong>📦 Elérhető darabszám:</strong> {{ $dish->stock }} db</li>
                </ul>
            </div>

            {{-- Jobb oldal --}}
            <div class="col-md-7">
                {{-- Méretválasztó --}}

@if($dish->size_options)
    @php $sizes = $dish->size_options; @endphp
    <div class="mb-3">
        <label for="size" class="form-label">Méret:</label>
        <select name="size" id="size" class="form-select">
            @foreach($sizes as $label => $option)
                @php
                    $finalPrice = $dish->getDiscountedSizePrice($label);
                @endphp
                <option value="{{ $label }}" {{ $label === 'Normál' ? 'selected' : '' }}>
                    {{ $label }} ({{ number_format($finalPrice, 0, '', ' ') }} Ft)
                </option>
            @endforeach
        </select>
    </div>
@endif


                {{-- Extra hozzávalók --}}
                @if($dish->extra_ingredients)
    @php
        $extras = $dish->extra_ingredients;
        $modifiers = $dish->ingredient_modifiers;
    @endphp
                    <div class="mb-3">
                        <label class="form-label">Extra hozzávalók:</label>
                        @foreach($extras as $extra)
                            @php
                                $modifier = $modifiers[$extra] ?? 0;
                                $gross = round($modifier);
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input extra-ingredient" type="checkbox" name="extra_ingredients[]" value="{{ $extra }}" data-price="{{ $gross }}">
                                <label class="form-check-label">
                                    {{ $extra }}
                                    @if($modifier > 0)
                                        (+{{ number_format($gross, 0, '', ' ') }} Ft)
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Kizárandó összetevők --}}
                @if($dish->base_ingredients)
    @php $ingredients = $dish->base_ingredients; @endphp
    <div class="mb-3">
        <label class="form-label">Kizárandó összetevők:</label>
        @foreach($ingredients as $ingredient)
            @php
                $discount = $modifiers[$ingredient] ?? 0;
                $gross = round($discount);
            @endphp
                            <div class="form-check">
                                <input class="form-check-input excluded-ingredient" type="checkbox" name="excluded_ingredients[]" value="{{ $ingredient }}" data-price="{{ $gross }}">
                                <label class="form-check-label">
                                    {{ $ingredient }}
                                    @if($discount != 0)
                                        ({{ $gross > 0 ? '+' : '' }}{{ number_format($gross, 0, '', ' ') }} Ft)
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Mennyiség --}}
                <div class="mb-3">
                    <label for="quantity" class="form-label">Mennyiség:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $dish->stock }}">
                </div>

                {{-- Ármegjelölés --}}
                <div class="mb-3">
                    <strong>Árak:</strong><br>
                    <span id="price" class="fw-bold text-dark">– Ft</span><br>
                    @if ($dish->on_sale && $dish->discount_percent > 0)
                        <small id="original-price" class="text-muted d-block mt-1"></small>
                    @endif
                </div>

                {{-- Kosárba helyezés --}}
                <button type="submit" class="btn btn-success w-100 mb-2">Kosárba helyezés</button>

                {{-- Vissza az étlapra --}}
                <div class="text-center">
                    <a href="{{ route('menu') }}" class="btn btn-outline-secondary">⬅️Vissza az étlapra</a>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- JavaScript: dinamikus árkalkuláció --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const basePrice = {{ $dish->gross_price }};
    const taxPercent = {{ $dish->tax_percent ?? 27 }};
    const discountPercent = {{ $dish->on_sale ? ($dish->discount_percent ?? 0) : 0 }};
    const sizeOptions = @json($dish->size_options);
    const ingredientModifiers = @json($dish->ingredient_modifiers);

    const sizeSelect = document.getElementById('size');
    const extraCheckboxes = document.querySelectorAll('.extra-ingredient');
    const excludedCheckboxes = document.querySelectorAll('.excluded-ingredient');
    const quantityInput = document.getElementById('quantity');
    const priceDisplay = document.getElementById('price');

    function calculatePrice() {
        let selectedSize = sizeSelect?.value || null;
        let sizeData = selectedSize && sizeOptions[selectedSize]
    ? sizeOptions[selectedSize]
    : { multiplier: 1.0 };

        let multiplier = parseFloat(sizeData.multiplier || 1.0);

        let taxRate = taxPercent / 100;
        let quantity = parseInt(quantityInput?.value || 1);

        let baseNet = (basePrice * multiplier)
        let discount = discountPercent > 0 ? baseNet * (discountPercent / 100) : 0;
        let netAfterDiscount = baseNet - discount;
        let grossDishPrice = netAfterDiscount;

        let extrasTotal = 0;
        extraCheckboxes.forEach(cb => {
            if (cb.checked) {
                extrasTotal += parseInt(cb.dataset.price || 0);
            }
        });

        let exclusionsTotal = 0;
        excludedCheckboxes.forEach(cb => {
            if (cb.checked) {
                exclusionsTotal += parseInt(cb.dataset.price || 0);
            }
        });

        let totalGross = (grossDishPrice + extrasTotal + exclusionsTotal) * quantity;

        if (priceDisplay) {
            priceDisplay.textContent = Math.round(totalGross).toLocaleString('hu-HU') + ' Ft';
        }

        const originalPriceDisplay = document.getElementById('original-price');
        if (originalPriceDisplay && discountPercent > 0) {
            const grossOriginalPrice = basePrice * multiplier;
            originalPriceDisplay.innerHTML =
                `Eredeti ár: <del>${Math.round(grossOriginalPrice).toLocaleString('hu-HU')} Ft</del> • Kedvezmény: –${discountPercent}%`;
        }
    }

    // Eseményfigyelők
    sizeSelect?.addEventListener('change', calculatePrice);
    quantityInput?.addEventListener('input', calculatePrice);
    extraCheckboxes.forEach(cb => cb.addEventListener('change', calculatePrice));
    excludedCheckboxes.forEach(cb => cb.addEventListener('change', calculatePrice));

    // Első kalkuláció betöltéskor
    calculatePrice();
});
</script>
@endsection
