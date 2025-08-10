@extends('layout')

@section('content')
<div class="container py-4">
    {{-- 📝 Cím a szerkesztő oldalhoz --}}
    <h2 class="mb-4">Fiók frissítése</h2>

    {{-- 📬 Űrlap a profiladatok frissítéséhez --}}
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf {{-- 🔐 Laravel CSRF token a biztonságos POST kéréshez --}}
        @method('PUT') {{-- 🛠️ HTTP PUT metódus, mivel frissítést végzünk --}}

        {{-- 📧 Új e-mail cím mező --}}
        <div class="mb-3">
            <label for="email" class="form-label">Új e-mail cím</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control"
                   value="{{ old('email', $user->email) }}"
                   required>
        </div>

        {{-- 🔐 Új jelszó mező --}}
        <div class="mb-3">
            <label for="password" class="form-label">Új jelszó</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control"
                   required>
        </div>

        {{-- 🔐 Jelszó megerősítése --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   class="form-control"
                   required>
        </div>

        {{-- 💾 Mentés gomb --}}
        <button type="submit" class="btn btn-success">
            ✅ Mentés
        </button>
    </form>
</div>
@endsection
