@extends('layout')

@section('content')
<div class="container">

<div class="mb-3 text-start">
    <a href="{{ route('menu') }}" class="btn btn-outline-secondary">
        ← Vissza az étlapra
    </a>
</div>

    <h2 class="mb-4">Saját rendeléseim</h2>

    {{-- Szűrés és rendezés --}}
<form method="GET" action="{{ route('orders.myorders') }}" class="row g-3 mb-4">

    {{-- Rendelés státusz --}}
    <div class="col-md-3">
        <label for="status" class="form-label">Rendelés státusz</label>
        <select name="status" id="status" class="form-select">
            <option value="" {{ request('status') === '' ? 'selected' : '' }}>Mind</option>
            <option value="uj" {{ request('status') === 'uj' ? 'selected' : '' }}>Új</option>
            <option value="keszul" {{ request('status') === 'keszul' ? 'selected' : '' }}>Készül</option>
            <option value="atvetelre_kesz" {{ request('status') === 'atvetelre_kesz' ? 'selected' : '' }}>Átvételre kész</option>
            <option value="atvetel_megtortent" {{ request('status') === 'atvetel_megtortent' ? 'selected' : '' }}>Átvétel megtörtént</option>
            <option value="kiszallitva" {{ request('status') === 'kiszallitva' ? 'selected' : '' }}>Kiszállítva</option>
            <option value="lezarva" {{ request('status') === 'lezarva' ? 'selected' : '' }}>Lezárva</option>
            <option value="torolve" {{ request('status') === 'torolve' ? 'selected' : '' }}>Törölve</option>
        </select>
    </div>

    {{-- Átvételi mód --}}
    <div class="col-md-3">
        <label for="delivery_method" class="form-label">Átvételi mód</label>
        <select name="delivery_method" id="delivery_method" class="form-select">
            <option value="" {{ request('delivery_method') === '' ? 'selected' : '' }}>Mind</option>
            <option value="delivery" {{ request('delivery_method') === 'delivery' ? 'selected' : '' }}>Kiszállítás</option>
            <option value="dine-in" {{ request('delivery_method') === 'dine-in' ? 'selected' : '' }}>Helyben fogyasztás</option>
            <option value="pickup" {{ request('delivery_method') === 'pickup' ? 'selected' : '' }}>Személyes átvétel</option>
        </select>
    </div>

    {{-- Fizetési állapot --}}
    <div class="col-md-3">
        <label for="payment_status" class="form-label">Fizetési állapot</label>
        <select name="payment_status" id="payment_status" class="form-select">
            <option value="" {{ request('payment_status') === '' ? 'selected' : '' }}>Mind</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Fizetve</option>
            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Nincs fizetve</option>
        </select>
    </div>

    {{-- Asztalfoglalás státusz --}}
    <div class="col-md-3">
        <label for="booking_status" class="form-label">Foglalás státusz</label>
        <select name="booking_status" id="booking_status" class="form-select">
            <option value="" {{ request('booking_status') === '' ? 'selected' : '' }}>Mind</option>
            <option value="uj" {{ request('booking_status') === 'uj' ? 'selected' : '' }}>Új</option>
            <option value="teljesitve" {{ request('booking_status') === 'teljesitve' ? 'selected' : '' }}>Teljesítve</option>
            <option value="elutasitva" {{ request('booking_status') === 'elutasitva' ? 'selected' : '' }}>Elutasítva</option>
            <option value="torolve" {{ request('booking_status') === 'torolve' ? 'selected' : '' }}>Törölve</option>
        </select>
    </div>

    {{-- Rendezés --}}
    <div class="col-md-3">
        <label for="sort" class="form-label">Rendezés</label>
        <select name="sort" id="sort" class="form-select">
            <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>Idő szerint ↓</option>
            <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Idő szerint ↑</option>
        </select>
    </div>

    <div class="col-12 text-end">
        <button type="submit" class="btn btn-dark">Szűrés</button>
    </div>
</form>

    @if ($orders->isEmpty())
        <p>Még nincs leadott rendelésed.</p>
    @else
        @foreach ($orders as $order)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <span>Rendelés #{{ $order->orders_id }} – {{ $order->status_label }}</span>
                    <span>{{ $order->created_at->format('Y.m.d H:i') }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Átvételi mód:</strong> {{ $order->delivery_method_label }}</p>
                    <p><strong>Fizetendő összeg:</strong> {{ number_format($order->total_price, 0, ',', ' ') }} Ft</p>

                    <p>
    <strong>Fizetési állapot:</strong>
    @if ($order->is_paid)
        <span class="text-success">Fizetve ({{ ucfirst($order->payment_method) }})</span>
    @else
        <span class="text-danger">Még nincs fizetve</span>
    @endif
</p>

@if ($order->is_paid)
    <a href="{{ route('order.invoice', $order->orders_id) }}"
       class="btn btn-outline-secondary btn-sm mb-2"
       target="_blank"
       title="Számla letöltése PDF-ben">
        <i class="bi bi-file-earmark-text"></i> Számla PDF
    </a>
@endif

                    {{-- Részletes díjak --}}
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
    <strong>{{ $item->dish->name }}</strong> x {{ $item->quantity }}
    <small class="text-muted">({{ $item->size }})</small>
</div>
                                <div>
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} Ft

                                </div>
                            </li>
                        @endforeach
                    </ul>

{{-- Rendelés értékelése – csak fizetett rendelés esetén --}}
@if (
    $order->is_paid &&
    is_null($order->rating_star) &&
    in_array($order->status, ['atvetel_megtortent', 'kiszallitva', 'lezarva'])
)
    <div class="mt-3 p-3 border rounded bg-light">
        <h5 class="mb-2">Rendelés értékelése</h5>

        @if ($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form method="POST" action="{{ route('order.rate', $order->orders_id) }}">
            @csrf

            {{-- Csillagok --}}
            <div class="mb-2">
                <label for="rating_star" class="form-label">Értékelés (1-5 csillag):</label>
                <select name="rating_star" id="rating_star" class="form-select" required>
                    <option value="" disabled selected>– Válassz –</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} csillag</option>
                    @endfor
                </select>
            </div>

            {{-- Megjegyzés --}}
            <div class="mb-2">
                <label for="rating_comment" class="form-label">Megjegyzés (opcionális):</label>
                <textarea name="rating_comment" id="rating_comment"
          class="form-control"
          rows="2"
          maxlength="300"
          placeholder="Pl. Finom volt minden étel."></textarea>
        <small class="form-text text-muted">
        Opcionális. Maximum 300 karakter. HTML, szkript és speciális karakterek nem engedélyezettek.
        </small>
            </div>

            <button type="submit" class="btn btn-outline-success btn-sm">Értékelés mentése</button>
        </form>
    </div>
@elseif ($order->rating_star)
    <div class="mt-3 p-3 border rounded bg-light">
        <h5 class="mb-2">Értékelés</h5>
        <p><strong>Csillagok:</strong> {{ $order->rating_star }} / 5</p>
        @if ($order->rating_comment)
            <p><strong>Megjegyzés:</strong> {{ $order->rating_comment }}</p>
        @endif
    </div>
@endif

{{-- Asztalfoglalás blokk --}}
@if ($order->delivery_method === 'dine-in')
    <div class="mt-3 p-3 border rounded bg-light">
        <h5 class="mb-2">Asztalfoglalás</h5>

        @if ($order->booking)
            {{-- Foglalás adatai --}}
            <p><strong>Státusz:</strong> {{ $order->booking->status_label }}</p>
            <p><strong>Időpont:</strong> {{ $order->booking->booking_time->format('Y.m.d H:i') }}</p>
            <p><strong>Fő:</strong> {{ $order->booking->seats }}</p>
            <p><strong>Asztalok:</strong> {{ $order->booking->table_code }}</p>

            {{-- Lemondás lehetősége --}}
            @if (
                in_array($order->status, ['uj','keszul','atvetelre_kesz']) &&
                !in_array($order->booking->status, ['elutasitva', 'torolve']) &&
                !$order->is_paid
            )
                <form method="POST" action="{{ route('bookings.cancel', $order->booking->bookings_id) }}" onsubmit="return confirm('Biztosan lemondod az asztalfoglalást?');">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Foglalás lemondása</button>
                </form>
            @endif
        @else
            {{-- Foglalás indítása csak akkor, ha még nincs foglalás és státusz engedélyezett --}}
            @if (in_array($order->status, ['uj','keszul','atvetelre_kesz']))
                <form method="GET" action="{{ route('bookings.create', $order->orders_id) }}">
                    <button class="btn btn-outline-primary btn-sm">Asztalfoglalás indítása</button>
                </form>
            @endif
        @endif
    </div>
@endif


{{-- Rendelés törlése csak 'uj', 'keszul', 'atvetelre_kesz' státusz esetén --}}
@if (in_array($order->status, ['uj', 'keszul', 'atvetelre_kesz']) && !$order->is_paid)
    <form method="POST" action="{{ route('order.cancel', $order->orders_id) }}" class="mt-2" onsubmit="return confirm('Biztosan törölni szeretnéd ezt a rendelést?');">
        @csrf
        <button class="btn btn-outline-danger">Rendelés törlése</button>
    </form>
@endif

{{-- Fizetés szimulálása, ha még nincs fizetve és státusz engedélyezett --}}
@if (in_array($order->status, ['uj', 'keszul', 'atvetelre_kesz']) && !$order->is_paid)
    <form method="GET" action="{{ route('order.pay', $order->orders_id) }}" class="mt-2">
        <button class="btn btn-success">Fizetés indítása</button>
    </form>
@endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
