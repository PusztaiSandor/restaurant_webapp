<!DOCTYPE html>
<html lang="hu">

<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #222;
      line-height: 1.5;
    }

    h1 {
      font-size: 22px;
      margin-bottom: 5px;
      color: #2c3e50;
    }

    h3 {
      font-size: 14px;
      margin-top: 20px;
      margin-bottom: 10px;
      color: #34495e;
      border-bottom: 1px solid #ccc;
      padding-bottom: 4px;
    }

    .section {
      margin-bottom: 30px;
    }

    .footer {
      margin-top: 50px;
      font-size: 10px;
      text-align: center;
      color: #666;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 11.5px;
    }

    .table th {
      background-color: #f9f9f9;
      text-align: left;
      padding: 6px;
      border-bottom: 2px solid #bbb;
    }

    .table td {
      padding: 6px;
      border-bottom: 1px solid #ddd;
    }

    .table tbody tr:nth-child(even) {
      background-color: #f6f6f6;
    }

    strong {
      color: #2c3e50;
    }
  </style>
</head>

<body>
  <h1>Esszencia Étterem – Számla</h1>
  <p><em>Az ízek és az oktatás esszenciája</em></p>

  <div class="section">
    <strong>Rendelés #{{ $order->orders_id }}</strong><br>
    Dátum: {{ $order->created_at->format('Y.m.d H:i') }}<br>
    Átvételi mód: {{ $order->delivery_method_label }}<br>
    Fizetési mód: {{ ucfirst($order->payment_method) }}<br>
    Fizetve: {{ $order->is_paid ? 'Igen' : 'Nem' }}<br>
    Rendelés státusza: {{ $order->status_label }}<br>
  </div>

  @if ($order->booking)
    <div class="section">
      <h3>Asztalfoglalás</h3>
      <p><strong>Státusz:</strong> {{ $order->booking->status_label }}</p>
      <p><strong>Időpont:</strong> {{ $order->booking->booking_time->format('Y.m.d H:i') }}</p>
      <p><strong>Fő:</strong> {{ $order->booking->seats }}</p>
      <p><strong>Asztalok:</strong> {{ $order->booking->table_code }}</p>
    </div>
  @endif

  <div class="section">
    <h3>Tételek</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Étel</th>
          <th>Mennyiség</th>
          <th>Méret</th>
          <th>Részösszeg</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->items as $item)
          <tr>
            <td>{{ $item->dish->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->size }}</td>
            <td>{{ number_format($item->subtotal, 0, ',', ' ') }} Ft</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="section">
    <h3>Díjak</h3>
    <p>Ételek ára összesen: {{ number_format($order->subtotal_sum, 0, ',', ' ') }} Ft</p>
    @if ($order->delivery_fee > 0)
      <p>Szállítási díj: {{ number_format($order->delivery_fee, 0, ',', ' ') }} Ft</p>
    @endif
    @if ($order->service_fee > 0)
      <p>Szervizdíj: {{ number_format($order->service_fee, 0, ',', ' ') }} Ft</p>
    @endif
    @if ($order->cutlery_fee > 0)
      <p>Evőeszköz díj: {{ number_format($order->cutlery_fee, 0, ',', ' ') }} Ft</p>
    @endif
    @if ($order->discount > 0)
      <p>Kedvezmény: −{{ number_format($order->discount, 0, ',', ' ') }} Ft</p>
    @endif
    <p><strong>Végösszeg: {{ number_format($order->total_price, 0, ',', ' ') }} Ft</strong></p>
  </div>

  <div class="footer">
    © 2025 Esszencia Étterem. Minden jog fenntartva. Budapest, Magyarország | +36 1 234 5678 | info@esszencia.hu
  </div>
</body>

</html>
