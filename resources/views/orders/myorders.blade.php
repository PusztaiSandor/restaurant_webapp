@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Saját rendeléseim</h2>

    @if ($orders->isEmpty())
        <p>Még nincs leadott rendelésed.</p>
    @else
        @foreach ($orders as $order)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <span>Rendelés #{{ $order->orders_id }} – {{ ucfirst($order->status) }}</span>
                    <span>{{ $order->created_at->format('Y.m.d H:i') }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Átvételi mód:</strong> {{ $order->delivery_method }}</p>
                    <p><strong>Fizetendő összeg:</strong> {{ number_format($order->total_price, 0, ',', ' ') }} Ft</p>

                    {{-- 🧮 Részletes díjak --}}
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

    @if ($order->service_fee > 0)
        <li class="list-group-item d-flex justify-content-between">
            <span>Szervizdíj</span>
            <span>{{ number_format($order->service_fee, 0, ',', ' ') }} Ft</span>
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

                    <ul class="list-group mb-3">
                        @foreach ($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
    <strong>{{ $item->dish->name }}</strong> × {{ $item->quantity }}
    <small class="text-muted">({{ $item->size }})</small>
</div>
                                <div>
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} Ft

                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if ($order->status === 'uj')
    <form method="POST" action="{{ route('order.cancel', $order->orders_id) }}" class="mt-2" onsubmit="return confirm('Biztosan törölni szeretnéd ezt a rendelést?');">
        @csrf
        <button class="btn btn-outline-danger">Rendelés törlése</button>
    </form>
@endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
