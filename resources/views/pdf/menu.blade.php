<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 18px;
            margin-top: 30px;
            margin-bottom: 10px;
            border-left: 4px solid #6c757d;
            padding-left: 8px;
            background-color: #f2f2f2;
        }
        h3 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #444;
        }
        .section {
            margin-bottom: 25px;
        }
        .dish {
            margin-bottom: 12px;
        }
        .dish-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .dish-name {
            flex: 1;
        }
        .price {
            font-weight: bold;
        }
        .dish-price {
            white-space: nowrap;
            text-align: right;
            font-weight: bold;
            line-height: 1.4;
        }
        .original-price {
            text-decoration: line-through;
            color: #888;
            margin-right: 6px;
        }
        .dish-details {
            font-size: 11px;
            color: #555;
        }
        .size-info {
            font-size: 10px;
            color: #777;
        }
        .footer {
            margin-top: 50px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>{{ $restaurantName }}</h1>
    <p><em>{{ $slogan }}</em></p>

    @foreach ($dishesGrouped as $category => $types)
        <div class="section">
            <h2>{{ $category }}</h2>
            @foreach ($types as $type => $dishes)
                <h3>{{ $type }}</h3>
                @foreach ($dishes as $dish)
                    <div class="dish">
                        <div class="dish-row">
                            <div class="dish-name">
                                <strong>{{ $dish->name }}</strong> – {{ $dish->description }}
                            </div>
                            <div class="dish-price">
                                @if ($dish->on_sale)
    <div class="dish-price">
        <div class="original-price">{{ number_format($dish->gross_price, 0, ',', ' ') }} Ft</div>
        <div style="color: #d9534f; font-size: 11px;">Kedvezmény: −{{ $dish->discount_percent }}%</div>
        <div class="price">{{ number_format($dish->gross_price * (1 - $dish->discount_percent / 100), 0, ',', ' ') }} Ft</div>
    </div>
@else
    <div class="dish-price">
        <div class="price">{{ number_format($dish->gross_price, 0, ',', ' ') }} Ft</div>
    </div>
@endif
                            </div>
                        </div>
                        <div class="dish-details">
                            Kalória: {{ $dish->calories }} kcal |
                            @if ($dish->vegetarian) 🌱 Vegetáriánus @endif
                            @if (!empty($dish->allergens)) | Allergének: {{ is_array($dish->allergens) ? implode(', ', $dish->allergens) : $dish->allergens }} @endif
                        </div>
                        @if (!empty($dish->size_options))
    <div class="size-info">
        Méretek:
        @foreach ($dish->size_options as $label => $opt)
            {{ $label }}
            @if (isset($opt['amount']) && isset($opt['unit']) && isset($opt['multiplier']))
                ({{ $opt['amount'] }} {{ $opt['unit'] }}) × {{ $opt['multiplier'] }}
            @endif
            |
        @endforeach
    </div>
@endif
                    </div>
                @endforeach
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        Budapest, Magyarország • +36 1 234 5678 • info@esszencia.hu
    </div>
</body>
</html>
