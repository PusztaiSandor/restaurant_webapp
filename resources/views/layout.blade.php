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
            <a class="navbar-brand d-flex align-items-center" href="javascript:void(0)">
                <img src="{{ asset('assets/images/components/logo.png') }}" alt="Esszencia logó" height="40" class="me-2">
                <span>Esszencia Étterem</span>
            </a>

            {{-- 🔘 Mobil nézet gomb --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLinks">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarLinks">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0)"><i class="fa-solid fa-utensils me-1"></i> Étlap</a></li>
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0)"><i class="fa-solid fa-phone me-1"></i> Kapcsolat</a></li>
                </ul>

                {{-- 🎨 Téma választó --}}
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-brush me-1"></i> Témák
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            @foreach(['basis','brite','darkly','vapor','solar','minty','flatly','morph','united','zephyr'] as $theme)
                                <li><a class="dropdown-item theme-option" href="javascript:void(0)" data-theme="{{ $theme }}">{{ ucfirst($theme) }}</a></li>
                            @endforeach
                        </ul>
                    </li>
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
