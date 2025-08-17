@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Rendelés véglegesítése</h2>

    {{-- 💬 Visszajelzések --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- 🧍 Felhasználói adatok --}}
    <form method="POST" action="{{ route('order.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Név</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefonszám</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        {{-- 🚚 Átvételi mód --}}
        <div class="mb-3">
            <label for="delivery_method" class="form-label">Átvételi mód</label>
            <select name="delivery_method" id="delivery_method" class="form-select" required>
                <option value="delivery">Kiszállítás</option>
                <option value="pickup">Személyes átvétel</option>
                <option value="dine-in">Helyben fogyasztás</option>
            </select>
        </div>

        <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="cutlery_requested" id="cutlery_requested" value="1"
        {{ old('cutlery_requested') ? 'checked' : '' }}>
    <label class="form-check-label" for="cutlery_requested">
        Kérek evőeszközt (plusz díj lehetséges)
    </label>
</div>

        {{-- 🧮 Kosár összesítése --}}
        <h5 class="mt-4">Rendelés összesítése</h5>
        <ul class="list-group mb-3">
            @foreach ($cart as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <strong>{{ $item['name'] }}</strong> × {{ $item['quantity'] }}
                        @if (!empty($item['size']))
                            <small class="text-muted">({{ ucfirst($item['size']) }})</small>
                        @endif
                    </div>
                    <span>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} Ft</span>
                </li>
            @endforeach
        </ul>

        {{-- 💸 Globális díjak --}}
@php
    $deliveryMethod = old('delivery_method', 'delivery');
    $cutleryRequested = old('cutlery_requested');
    $baseTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
@endphp

@if (!empty($charges))
    <h6 class="mb-2">További díjak:</h6>
    <ul class="list-group mb-3" id="charge-list">
        @foreach ($charges as $charge)
            @php
                $apply = false;

                // Szállítási mód egyezés
                if ($charge['delivery_method'] === $deliveryMethod) {
                    // Ha nem választható, automatikusan alkalmazandó
                    if (!$charge['is_optional']) {
                        $apply = true;
                    }
                    // Ha választható, csak akkor alkalmazzuk, ha a felhasználó kérte
                    elseif ($charge['charge_type'] === 'cutlery' && $cutleryRequested) {
                        $apply = true;
                    }
                }

                $amount = $charge['is_percentage']
                    ? round($baseTotal * ($charge['value'] / 100), 2)
                    : $charge['value'];
            @endphp

            @if ($apply)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $charge['description'] ?? ucfirst(str_replace('_', ' ', $charge['charge_type'])) }}</span>
                    <span>
                        @if ($charge['is_percentage'])
                            {{ $charge['value'] }}% ({{ number_format($amount, 0, ',', ' ') }} Ft)
                        @else
                            {{ number_format($amount, 0, ',', ' ') }} Ft
                        @endif
                    </span>
                </li>
            @endif
        @endforeach
    </ul>
@endif

        {{-- 🧾 Végösszeg --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <strong>Végösszeg:</strong>
            <span id="final-total">{{ number_format($totalWithCharges, 0, ',', ' ') }} Ft</span>
        </div>

        {{-- ✅ Megrendelés gomb --}}
        <div class="text-end">
            <button type="submit" class="btn btn-success">Megrendelés elküldése</button>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deliverySelect = document.getElementById('delivery_method');
    const cutleryCheckbox = document.getElementById('cutlery_requested');
    const chargeList = document.getElementById('charge-list');
    const finalTotal = document.getElementById('final-total');

    const baseTotal = @json($subtotal);
    const charges = @json($charges);

    function updateCharges() {
        const method = deliverySelect.value;
        const cutlery = cutleryCheckbox.checked;
        let total = baseTotal;
        let html = '';

        charges.forEach(charge => {
            let apply = false;

            // Szállítási mód egyezés
            if (charge.delivery_method === method) {
                if (!charge.is_optional) {
                    apply = true;
                } else if (charge.charge_type.toLowerCase().includes('cutlery') && cutlery) {
                    apply = true;
                }
            }

            if (apply) {
                const amount = charge.is_percentage
                    ? Math.round(baseTotal * (charge.value / 100))
                    : charge.value;

                // Kedvezmény levonása
                if (charge.charge_type.toLowerCase().includes('discount')) {
                    total -= amount;
                } else {
                    total += amount;
                }

                html += `
                    <li class="list-group-item d-flex justify-content-between">
                        <span>${charge.description ?? charge.charge_type.replace('_', ' ')}</span>
                        <span>${charge.is_percentage ? charge.value + '% (' + amount + ' Ft)' : amount + ' Ft'}</span>
                    </li>
                `;
            }
        });

        chargeList.innerHTML = html;
        finalTotal.textContent = `${total.toLocaleString('hu-HU')} Ft`;
    }

    deliverySelect.addEventListener('change', updateCharges);
    cutleryCheckbox.addEventListener('change', updateCharges);

    updateCharges(); // első betöltéskor
});
</script>
@endsection
