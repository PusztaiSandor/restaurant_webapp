@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Rendelés fizetése – Rendelés #{{ $order->orders_id }}</h2>

    <p><strong>Fizetendő összeg:</strong> {{ number_format($order->total_price, 0, ',', ' ') }} Ft</p>

    {{-- @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <form method="POST" action="{{ route('order.pay.submit', $order->orders_id) }}">
        @csrf

        {{-- Fizetési mód választása --}}
        <div class="mb-3">
            <label for="payment_method" class="form-label">Fizetési mód</label>
            <select id="payment_method" name="payment_method" class="form-select" required>
                <option value="" disabled selected>– Válassz –</option>
                <option value="bankkartya">Bankkártya</option>
                <option value="szepkartya">SZÉP kártya</option>
                @if ($order->status === 'atvetelre_kesz')
                <option value="keszpenz">Készpénz</option>
                @endif
            </select>
        </div>

        {{-- Bankkártya / SZÉP kártya mezők (szimuláció) --}}
        <div id="card-fields" style="display: none;">
            <div class="alert alert-info">
                <strong>Szimulációs fizetés</strong>. Az adatok nem kerülnek mentésre.
            </div>

            <div class="mb-2">
                <label for="card_name" class="form-label">Kártyabirtokos neve</label>
                <input type="text" id="card_name" name="card_name" class="form-control">
            </div>

            <div class="mb-2">
                <label for="card_number" class="form-label">Kártyaszám</label>
                <input type="text" id="card_number" name="card_number" class="form-control" maxlength="19" placeholder="1234 5678 9012 3456">
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label for="card_expiry" class="form-label">Lejárat</label>
                    <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="card_cvc" class="form-label">CVC</label>
                    <input type="text" id="card_cvc" name="card_cvc" class="form-control" maxlength="4">
                </div>
            </div>
        </div>

        {{-- Készpénzes fizetés mező --}}
        <div id="cash-field" style="display: none;">
            <div class="mb-2">
                <label for="cash_given" class="form-label">Átadott összeg (Ft)</label>
                <input type="number" id="cash_given" name="cash_given" class="form-control" min="0" step="5">
                <div class="form-text">Az összegnek oszthatónak kell lennie 5-tel.</div>
            </div>
        </div>

        {{-- Szimulált fizetés gomb --}}
        <div class="text-end mt-4">
            <a href="{{ route('orders.myorders') }}" class="btn btn-secondary">⬅️Vissza a Rendeléseimhez</a>
            <button type="submit" class="btn btn-success">Fizetés indítása</button>
        </div>
    </form>
</div>

{{-- Dinamikus mezők megjelenítése --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const methodSelect = document.getElementById('payment_method');
    const cardFields = document.getElementById('card-fields');
    const cashField = document.getElementById('cash-field');

    methodSelect.addEventListener('change', function () {
        const method = this.value;

        cardFields.style.display = (method === 'bankkartya' || method === 'szepkartya') ? 'block' : 'none';
        cashField.style.display = (method === 'keszpenz') ? 'block' : 'none';
    });
});
</script>
@endsection
