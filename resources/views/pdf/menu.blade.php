<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px;}
        h1, h2 { color: #333; }
        .section { margin-bottom: 30px; }
        .dish { margin-bottom: 10px; }
        .price { font-weight: bold; }
        .original-price { text-decoration: line-through; color: #888; margin-right: 8px; }
        .footer { margin-top: 50px; font-size: 10px; text-align: center; color: #666; }
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
                        <strong>{{ $dish->name }}</strong> – {{ $dish->description }}<br>
                        Kalória: {{ $dish->calories }} kcal |
                        @if ($dish->vegetarian) 🌱 Vegetáriánus @endif |
                        Allergének: {{ is_array($dish->allergens) ? implode(', ', $dish->allergens) : $dish->allergens }}<br>
                        @if ($dish->on_sale)
                            <span class="original-price">{{ number_format($dish->gross_price, 0, ',', ' ') }} Ft</span>
                            <span class="price">{{ number_format($dish->gross_price * (1 - $dish->discount_percent / 100), 0, ',', ' ') }} Ft</span>
                            (−{{ $dish->discount_percent }}%)
                        @else
                            <span class="price">{{ number_format($dish->gross_price, 0, ',', ' ') }} Ft</span>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        {{ $footer }}
    </div>
</body>
</html>
