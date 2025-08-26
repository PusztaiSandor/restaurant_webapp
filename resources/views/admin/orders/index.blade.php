@extends('layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Összes rendelés (Admin)</h2>

    @if ($orders->isEmpty())
        <p>Nincs még rendelés az adatbázisban.</p>
    @else
        @foreach ($orders as $order)
            @php
                // Dinamikus státuszválasztó logika
                $statusOptions = [];

                switch ($order->status) {
                    case 'uj':
                        $statusOptions = ['keszul'];
                        break;
                    case 'keszul':
                        $statusOptions = ['atvetelre_kesz'];
                        break;
                    case 'atvetelre_kesz':
                        if ($order->delivery_method !== 'delivery' && $order->is_paid) {
                            $statusOptions = ['atvetel_megtortent'];
                        }
                        break;
                    case 'atvetel_megtortent':
                    case 'kiszallitva':
                        $statusOptions = ['lezarva'];
                        break;
                }
            @endphp

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Rendelés #{{ $order->orders_id }}</strong>
                        <span class="badge bg-secondary ms-2">{{ ucfirst($order->status) }}</span>
                        <span class="ms-3 text-muted">{{ $order->created_at->format('Y.m.d H:i') }}</span>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Összefoglaló adatok --}}
                    <p><strong>Felhasználó:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                    <p><strong>Átvételi mód:</strong> {{ ucfirst($order->delivery_method) }}</p>
                    <p><strong>Fizetve:</strong> {{ $order->is_paid ? 'Igen' : 'Nem' }}</p>

                    {{-- Tételek --}}
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

                    {{-- Díjak --}}
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

                    {{-- Státuszváltás --}}
                    @if (!in_array($order->status, ['lezarva', 'torolve']) && count($statusOptions) > 0)
                        <form method="POST" action="{{ route('admin.orders.updatestatus', $order->orders_id) }}" class="mb-2">
                            @csrf
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <select name="status" class="form-select">
                                    @foreach ($statusOptions as $status)
                                        <option value="{{ $status }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-success" type="submit">Mentés</button>
                            </div>
                        </form>
                    @endif

                    {{-- Futárhoz rendelés --}}
                    @if ($order->delivery_method === 'delivery' && $order->status === 'atvetelre_kesz' && !$order->courier_id)
                        <form method="POST" action="{{ route('admin.orders.assignCourier', $order->orders_id) }}">
                            @csrf
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <select name="courier_id" class="form-select" required>
                                    <option value="" disabled selected>– Válassz futárt –</option>
                                    @foreach ($couriers as $courier)
                                        <option value="{{ $courier->users_id }}">
                                            {{ $courier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-primary" type="submit">Futárhoz rendelés</button>
                            </div>
                        </form>
                    @endif

{{-- Értékelés megjelenítése (Admin) --}}
@if ($order->rating_star)
    <div class="mt-3 p-3 border rounded bg-light">
        <h5 class="mb-2">Felhasználói értékelés</h5>
        <p><strong>Csillagok:</strong> {{ $order->rating_star }} / 5</p>
        @if ($order->rating_comment)
            <p><strong>Megjegyzés:</strong> {{ $order->rating_comment }}</p>
        @endif
    </div>
@endif

                    {{-- Asztalfoglalás (Admin) --}}
@if ($order->delivery_method === 'dine-in' && $order->booking)
    <div class="mt-3 p-3 border rounded bg-light">
        <h5 class="mb-2">Asztalfoglalás</h5>

        <p><strong>Státusz:</strong> {{ ucfirst($order->booking->status) }}</p>
        <p><strong>Foglalási idő:</strong> {{ $order->booking->booking_time->format('Y.m.d H:i') }}</p>
        <p><strong>Fő:</strong> {{ $order->booking->seats }}</p>
        <p><strong>Asztalok:</strong> {{ $order->booking->table_code }}</p>

        {{-- Admin státuszváltás: csak ha még nincs véglegesítve --}}
        @if (in_array($order->booking->status, ['uj']))
            <form method="POST" action="{{ route('admin.bookings.updatestatus', $order->booking->bookings_id) }}" class="mt-2">
                @csrf
                <div class="input-group input-group-sm" style="max-width: 300px;">
                    <select name="status" class="form-select" required>
                        <option value="" disabled selected>– Válassz státuszt –</option>
                        <option value="teljesitve">Teljesítve</option>
                        <option value="elutasitva">Elutasítva</option>
                    </select>
                    <button class="btn btn-outline-success" type="submit">Mentés</button>
                </div>
            </form>
        @endif
    </div>
@endif

                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
