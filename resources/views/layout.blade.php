<!DOCTYPE html>
<html lang="hu">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Esszencia Étterem</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

  {{-- Bootstrap témaváltáshoz --}}
  <link id="theme-css" rel="stylesheet" href="{{ asset('assets/css/theme-darkly.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/mystyle.css') }}">

  {{-- Font Awesome ikonok (helyi fájlokból) --}}
  <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/solid.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/regular.css') }}">

</head>

<body class="bg-light">
  <header class="container-fluid bg-dark sticky-top">
    <nav class="navbar navbar-expand-xl navbar-dark container">
      {{-- Logó és kezdőlap hivatkozás --}}
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('assets/images/components/logo.png') }}" alt="Esszencia logó" height="40" class="me-2">
        <span>Esszencia Étterem</span>
      </a>

      {{-- Mobil nézet gomb --}}
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLinks">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarLinks">
        <ul class="navbar-nav me-auto align-items-center">
          @if (!auth()->check() || auth()->user()->role === 'user')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('menu') }}">
                <i class="fa-solid fa-utensils me-1"></i> Étlap
              </a>
            </li>
          @endif

          <li class="nav-item">
            <a class="nav-link" href="{{ route('contact') }}">
              <i class="fa-solid fa-phone me-1"></i> Kapcsolat
            </a>
          </li>
        </ul>

        {{-- Téma választó + Felhasználói menü --}}
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-brush me-1"></i> Témák
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">

              @php
                $themes = [
                    'basis' => 'Alap',
                    'brite' => 'Sötét',
                    'darkly' => 'Letisztult',
                    'flatly' => 'Lágy',
                    'united' => 'Modern',
                ];
              @endphp

              @foreach ($themes as $key => $label)
                <li>
                  <a class="dropdown-item theme-option" href="javascript:void(0)" data-theme="{{ $key }}">
                    {{ $label }}
                  </a>
                </li>
              @endforeach

            </ul>
          </li>

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

          @php
            $cart = session()->get('cart', []);
            $cartCount = array_sum(array_column($cart, 'quantity'));
          @endphp

          @if (!auth()->check() || auth()->user()->role === 'user')
            <li class="nav-item position-relative">
              <a class="nav-link" href="{{ route('cart.index') }}">
                <i class="fa-solid fa-cart-shopping me-1"></i> Kosár
                @if ($cartCount > 0)
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                  </span>
                @endif
              </a>
            </li>
          @endif

          {{-- Bejelentkezett felhasználók számára: Üdvözlés + Kilépés --}}
          @auth
            <li class="nav-item nav-link text-white d-flex align-items-center">
              <i class="fa-solid fa-user me-2"></i> Üdv, {{ Auth::user()->name }}!
            </li>

            @if (in_array(Auth::user()->role, ['admin', 'user', 'courier']))
              <li class="nav-item">
                <a class="nav-link" href="{{ route('profile') }}">
                  <i class="fa-solid fa-id-card me-1"></i> Profilom
                </a>
              </li>
            @endif

            {{-- "Rendeléseim" csak bejelentkezett "user" szerepkörű felhasználónak --}}
            @auth
              @if (Auth::user()->role === 'user')
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('orders.myorders') }}">
                    <i class="fa-solid fa-receipt me-1"></i> Rendeléseim
                  </a>
                </li>
              @endif
            @endauth

            {{-- Futár: Saját kiszállítási rendeléseim --}}
            @if (Auth::user()->role === 'courier')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('courier.orders.index') }}">
                  <i class="fa-solid fa-truck me-1"></i> Futár: Rendeléseim
                </a>
              </li>
            @endif

            {{-- Admin funkciók – csak admin szerepkör esetén jelenik meg --}}
            {{-- Admin lenyíló menü --}}
            @if (Auth::user()->role === 'admin')
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                  <i class="fa-solid fa-shield-halved me-1"></i> Admin menü
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                  <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i
                        class="fa-solid fa-users-gear me-1"></i> Felhasználók</a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.users.create') }}"><i
                        class="fa-solid fa-user-plus me-1"></i> Új felhasználó</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('admin.dishes.index') }}"><i
                        class="fa-solid fa-bowl-food me-1"></i> Ételek</a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.dishes.create') }}"><i
                        class="fa-solid fa-plus me-1"></i> Új étel</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}"><i
                        class="fa-solid fa-clipboard-list me-1"></i> Rendelések</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('global-charges.index') }}"><i
                        class="fa-solid fa-coins me-1"></i> Globális díjak</a></li>
                  <li><a class="dropdown-item" href="{{ route('global-charges.create') }}"><i
                        class="fa-solid fa-plus me-1"></i> Új díj</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('admin.tables.index') }}"><i
                        class="fa-solid fa-table me-1"></i> Asztalok</a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.tables.create') }}"><i
                        class="fa-solid fa-plus me-1"></i> Új asztal</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('admin.feedbacks') }}"><i
                        class="fa-solid fa-comment-dots me-1"></i> Visszajelzések</a></li>
                </ul>
              </li>
            @endif

            {{-- Kilépés gomb --}}
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
    <small><i class="fa-solid fa-clock me-2"></i> Nyitvatartás: 7/24 Minden nap 00:00 - 24:00</small>
  </div>


  <main class="container py-4">

    {{-- Flash üzenetek --}}
    @foreach (['success', 'error', 'info', 'warning'] as $type)
      @if (session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show"
          role="alert">
          <i
            class="fa-solid fa-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-triangle' : 'info-circle') }} me-2"></i>
          {{ session($type) }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Bezárás"></button>
        </div>
      @endif
    @endforeach

    @yield('content')
  </main>

  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
      <p class="mb-1">&copy; {{ date('Y') }} Esszencia Étterem. Minden jog fenntartva.</p>
      <p class="mb-0">
        <i class="fa-solid fa-location-dot me-1"></i> Budapest, Magyarország |
        <i class="fa-solid fa-phone me-1"></i> +36 1 234 5678 |
        <i class="fa-solid fa-envelope me-1"></i> info@esszencia.hu
      </p>
      <p class="mt-3">
        <a href="{{ route('terms') }}" class="text-light hover:underline text-sm">
          Általános Felhasználási Feltételek megtekintése
        </a>
      </p>
    </div>
  </footer>

  {{-- JS fájlok (helyi fájlokból) --}}
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

  {{-- Téma-váltó működés --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const themeOptions = document.querySelectorAll('.theme-option');
      const themeLink = document.getElementById('theme-css');
      const savedTheme = localStorage.getItem('selectedTheme');

      if (savedTheme && themeLink) {
        themeLink.href = '/assets/css/theme-' + savedTheme + '.css';
      }

      themeOptions.forEach(option => {
        option.addEventListener('click', function(e) {
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
