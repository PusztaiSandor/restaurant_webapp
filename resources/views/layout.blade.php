<!DOCTYPE html>
<html lang="hu">
<head>
    {{-- 🔤 Karakterkódolás és reszponzív nézet --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 🏷️ Oldal címe --}}
    <title>Esszencia Étterem</title>

    {{-- 🎨 Bootstrap témaváltáshoz --}}
    <link id="theme-css" rel="stylesheet" href="{{ asset('assets/css/theme-darkly.css') }}">

    {{-- 🧾 Saját stíluslap --}}
    <link rel="stylesheet" href="{{ asset('assets/css/mystyle.css') }}">

    {{-- ⭐ Font Awesome ikonok (helyi fájlokból) --}}
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/solid.css') }}">
</head>
<body class="bg-light">
    {{-- 🔝 Navigációs fejléc --}}
    <header class="container-fluid bg-dark sticky-top">
        <nav class="navbar navbar-expand-md navbar-dark container">
            {{-- 🔗 Logó és kezdőlap hivatkozás --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/components/logo.png') }}" alt="Esszencia logó" height="40" class="me-2">
                <span>Esszencia Étterem</span>
            </a>

            {{-- 🔘 Mobil nézet gomb --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLinks">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarLinks">
    <ul class="navbar-nav me-auto">
        {{-- 🍽️ Étlap link --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('menu') }}">
                <i class="fa-solid fa-utensils me-1"></i> Étlap
            </a>
        </li>

        {{-- 📞 Kapcsolat link --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fa-solid fa-phone me-1"></i> Kapcsolat
            </a>
        </li>
    </ul>

    {{-- 🎨 Téma választó + Felhasználói menü --}}
    <ul class="navbar-nav ms-auto align-items-center">
        {{-- 🎨 Téma választó --}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fa-solid fa-brush me-1"></i> Témák
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">
                @foreach(['basis','brite','darkly','vapor','solar','minty','flatly','morph','united','zephyr'] as $theme)
                    <li>
                        <a class="dropdown-item theme-option" href="javascript:void(0)" data-theme="{{ $theme }}">
                            {{ ucfirst($theme) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        {{-- 🔐 Vendég felhasználók számára: Belépés és Regisztráció --}}
        @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Bejelentkezés
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">
                    <i class="fa-solid fa-user-plus me-1"></i> Regisztráció
                </a>
            </li>
        @endguest

        {{-- ✅ Bejelentkezett felhasználók számára: Üdvözlés + Kilépés --}}
        @auth
            {{-- 👋 Üdvözlés névvel --}}
            <li class="nav-item nav-link text-white d-flex align-items-center">
                <i class="fa-solid fa-user me-2"></i> Üdv, {{ Auth::user()->name }}!
            </li>


            {{-- 👤 "Profilom" minden szerepkör számára --}}
@if(in_array(Auth::user()->role, ['admin', 'user', 'courier']))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fa-solid fa-id-card me-1"></i> Profilom
        </a>
    </li>
@endif

{{-- 🧾 "Rendeléseim" csak "user" szerepkörre --}}
@if(Auth::user()->role === 'user')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('mypage.orders') }}">
            <i class="fa-solid fa-receipt me-1"></i> Rendeléseim
        </a>
    </li>
@endif

{{-- 🛡️ Admin funkciók – csak admin szerepkör esetén jelenik meg --}}
@if(Auth::user()->role === 'admin')
    {{-- 👥 Felhasználók kezelése --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fa-solid fa-users-gear me-1"></i> Admin: Felhasználók
        </a>
    </li>

    {{-- ➕ Új felhasználó létrehozása --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.create') }}">
            <i class="fa-solid fa-user-plus me-1"></i> Admin: Új felhasználó
        </a>
    </li>

{{-- 🍽️ Admin: Ételkezelés --}}
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.dishes.index') }}">
        <i class="fa-solid fa-bowl-food me-1"></i> Admin: Ételek
    </a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.dishes.create') }}">
        <i class="fa-solid fa-plus me-1"></i> Admin: Új étel
    </a>
</li>

@endif

            {{-- 🔓 Kilépés gomb (POST metódus) --}}
            <li class="nav-item d-flex align-items-center">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link px-0 text-white d-flex align-items-center">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Kilépés
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</div>
        </nav>
    </header>
     <div class="opening-hours">
    <small><i class="fa-solid fa-clock me-2"></i> Nyitvatartás: 7/24 Minden nap 00:00 – 24:00</small>
    </div>

    {{-- 📦 Tartalom helye --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- 🔻 Lábléc --}}
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1">&copy; {{ date('Y') }} Esszencia Étterem. Minden jog fenntartva.</p>
            <p class="mb-0">
                <i class="fa-solid fa-location-dot me-1"></i> Budapest, Magyarország |
                <i class="fa-solid fa-phone me-1"></i> +36 1 234 5678 |
                <i class="fa-solid fa-envelope me-1"></i> info@esszencia.hu
            </p>
        </div>
    </footer>

    {{-- 🔧 JS fájlok (helyi fájlokból) --}}
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    {{-- 🎨 Téma-váltó működés --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeOptions = document.querySelectorAll('.theme-option');
        const themeLink = document.getElementById('theme-css');
        const savedTheme = localStorage.getItem('selectedTheme');

        if (savedTheme && themeLink) {
            themeLink.href = '/assets/css/theme-' + savedTheme + '.css';
        }

        themeOptions.forEach(option => {
            option.addEventListener('click', function (e) {
                e.preventDefault();
                const selectedTheme = this.getAttribute('data-theme');
                localStorage.setItem('selectedTheme', selectedTheme);
                if (themeLink) {
                    themeLink.href = '/assets/css/theme-' + selectedTheme + '.css';
                }
            });
        });
    });
    </script>
</body>
</html>
