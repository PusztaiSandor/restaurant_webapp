@extends('layout')

@section('content')
<section class="text-center py-5">
    {{-- 🏠 Üdvözlő szöveg --}}
    <h1 class="display-5 mb-3">Üdvözlünk az Esszencia Étteremben!</h1>
    <p class="lead mb-4">Az ízek lényege — friss, fenntartható, barátságos.</p>

    {{-- 🖼️ Étterem logó megjelenítése --}}
    <img src="{{ asset('assets/images/components/logo.png') }}" alt="Esszencia Étterem logó" class="img-fluid my-4" style="max-height: 150px;">

    {{-- 🔐 Dinamikus üzenet a felhasználó állapota szerint --}}
    @guest
        <p class="fs-5">
            A rendeléshez kérlek <a href="{{ route('login') }}" class="text-decoration-underline">jelentkezz be</a>
            vagy <a href="{{ route('register') }}" class="text-decoration-underline">regisztrálj</a>.
        </p>
    @else
        <p class="fs-5">Örülünk, hogy újra itt vagy, <strong>{{ Auth::user()->name }}</strong>!</p>
    @endguest

    @if(!auth()->check() || auth()->user()->role === 'user')
    <!-- 🍽️ Kínálatunkból – Véletlenszerű ételkártyák -->
<section class="container py-5">
  <h2 class="mb-4 text-center">Kínálatunkból</h2>

  <div class="row">
    @foreach ($randomDishes as $dish)
      @php
        $sizeOptions = $dish->size_options ?? [];
        $defaultSize = isset($sizeOptions['Normál']) ? 'Normál' : array_key_first($sizeOptions);
        $finalPrice = $dish->getDiscountedSizePrice($defaultSize);
        $originalPrice = $dish->getOriginalPrice($defaultSize);
      @endphp

      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm d-flex flex-column" id="dish_{{ $dish->dishes_id }}">
          @if ($dish->image)
            <img src="{{ asset('assets/images/termekek/' . $dish->image) }}" class="card-img-top" alt="{{ $dish->name }}">
          @endif

          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $dish->name }}</h5>

            <div class="mb-3">
              <strong>Ár:</strong><br>
              <span id="price_{{ $dish->dishes_id }}" class="fw-bold text-dark">
                {{ number_format($finalPrice, 0, ',', ' ') }} Ft
              </span><br>

              @if ($dish->on_sale && $dish->discount_percent > 0)
                <small id="discount_{{ $dish->dishes_id }}" class="text-muted d-block mt-1">
                  Eredeti ár: <del id="original_{{ $dish->dishes_id }}">
                    {{ number_format($originalPrice, 0, ',', ' ') }} Ft
                  </del> • Kedvezmény: –{{ $dish->discount_percent }}%
                </small>
              @endif
            </div>

            <p class="card-text"><strong>Elérhető:</strong> {{ $dish->stock }} db</p>

            <div class="mt-auto">
              <a href="{{ route('dishes.show', ['dish' => $dish->dishes_id]) }}" class="btn btn-sm btn-success w-100">
                Részletek és rendelés
              </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

    {{-- 🍽️ Étlap gomb – az étlapra mutat --}}
    <div class="mt-4">
        <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-utensils me-2"></i> Nézd meg az étlapot
        </a>
    </div>
    @endif

<!-- 💬 Rólunk írták – kiemelt értékelések -->
<section class="container py-5">
  <h2 class="mb-4 text-center">Rólunk írták</h2>

  <div class="row">
    @forelse ($highlightedFeedbacks as $fb)
      <div class="col-md-6 mb-4">
        <div class="border rounded p-4 shadow-sm h-100 bg-light">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>{{ $fb->user->name ?? 'Vendég' }}</strong>
            <span class="text-warning">
              @for ($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= $fb->rating ? 's' : 'r' }} fa-star"></i>
              @endfor
            </span>
          </div>
          @if ($fb->subject)
            <h6 class="text-muted mb-2">{{ $fb->subject }}</h6>
          @endif
          <p class="mb-0">{{ $fb->content }}</p>
        </div>
      </div>
    @empty
      <p class="text-center text-muted">Még nincsenek kiemelt értékelések.</p>
    @endforelse
  </div>
</section>

</section>
@endsection
