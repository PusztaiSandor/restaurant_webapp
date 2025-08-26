@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Saját kiszállítási rendeléseim</h2>

    @if ($orders->isEmpty())
        <p>Nincs hozzád rendelt kiszállítási rendelés.</p>
    @else
        @foreach ($orders as $order)
            <div class="card mb-4">
                {{-- Fejléc: Rendelés azonosító + státusz + időpont --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Rendelés #{{ $order->orders_id }}</strong>
                        <span class="badge bg-secondary ms-2">{{ $order->status_label }}</span>
                        <span class="ms-3 text-muted">{{ $order->created_at->format('Y.m.d H:i') }}</span>
                    </div>

                    {{-- Státuszváltás – csak „atvetelre_kesz” esetén jelenik meg --}}
                    @if ($order->status === 'atvetelre_kesz' && $order->is_paid)
                        <form method="POST" action="{{ route('courier.orders.markDelivered', $order->orders_id) }}">
                            @csrf
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <select class="form-select" disabled>
                                    <option selected>Kiszállítva</option>
                                </select>
                                <button class="btn btn-outline-success" type="submit">Mentés</button>
                            </div>
                        </form>
                    @elseif ($order->status === 'kiszallitva')
                        <div class="text-success fw-bold">Kiszállítva – admin lezárásra vár</div>
                    @elseif ($order->status === 'lezarva')
                        <div class="text-muted">Lezárva</div>
                    @endif
                </div>

                {{-- Rendelés részletei --}}
                <div class="card-body">
                    <p><strong>Felhasználó:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                    <p><strong>Átvételi mód:</strong> {{ $order->delivery_method_label }}</p>
                    <p><strong>Fizetve:</strong> {{ $order->is_paid ? 'Igen' : 'Nem' }}</p>

                    {{-- Tételek listája --}}
                    <ul class="list-group mb-3">
                        @foreach ($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item->dish->name }}</strong> × {{ $item->quantity }}
                                    <small class="text-muted">({{ $item->size }})</small>
                                </div>
                                <div>{{ number_format($item->subtotal, 0, ',', ' ') }} Ft</div>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Díjak és végösszeg --}}
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ételek ára összesen</span>
                            <span>{{ number_format($order->subtotal_sum, 0, ',', ' ') }} Ft</span>
                        </li>
                        @if ($order->delivery_fee > 0)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Szállítási díj</span>
                                <span>{{ number_format($order->delivery_fee, 0, ',', ' ') }} Ft</span>
                            </li>
                        @endif
                        @if ($order->cutlery_fee > 0)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Evőeszköz díj</span>
                                <span>{{ number_format($order->cutlery_fee, 0, ',', ' ') }} Ft</span>
                            </li>
                        @endif
                        @if ($order->discount > 0)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Kedvezmény</span>
                                <span>−{{ number_format($order->discount, 0, ',', ' ') }} Ft</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Végösszeg</strong>
                            <strong>{{ number_format($order->total_price, 0, ',', ' ') }} Ft</strong>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
