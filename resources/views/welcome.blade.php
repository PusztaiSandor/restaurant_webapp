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

    {{-- 🍽️ Étlap gomb – jelenleg a kezdőlapra mutat --}}
    <div class="mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-utensils me-2"></i> Nézd meg az étlapot
        </a>
    </div>
</section>
@endsection
