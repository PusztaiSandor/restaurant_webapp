@extends('layout')

@section('content')
<div class="container py-4">
    {{-- 📝 Cím a szerkesztő oldalhoz --}}
    <h2 class="mb-4">Fiók frissítése</h2>

 {{-- ⚠️ Figyelmeztetés ideiglenes jelszóra --}}
    @if($user->must_change_password)
        <div class="alert alert-warning">
            <i class="fa-solid fa-key me-1"></i>
            Az e-mail címed és jelszavad ideiglenes. Kérjük, mielőbb állíts be saját e-mail címet és jelszót a biztonság érdekében!
        </div>
    @endif

    {{-- 📬 Űrlap a profiladatok frissítéséhez --}}
    <form method="POST" action="{{ route('profile.credentials') }}">
        @csrf {{-- 🔐 Laravel CSRF token a biztonságos POST kéréshez --}}
        {{--@method('PUT') 🛠️ HTTP PUT metódus, mivel frissítést végzünk--}}

        {{-- 📧 Új e-mail cím mező --}}
        <div class="mb-3">
    <label for="email" class="form-label">Új e-mail cím</label>
    <input type="email"
           name="email"
           id="email"
           class="form-control"
           value="{{ old('email', $user->email) }}"
           required
           minlength="5"
           maxlength="60"
           pattern="^[A-Za-z0-9@.]{5,60}$">
    <small class="form-text text-muted">
        Csak betűk, számok, pont és @ karakter. Minimum 5, maximum 60 karakter.
    </small>
</div>

        {{-- 🔐 Új jelszó mező --}}
        <div class="mb-3">
    <label for="password" class="form-label">Új jelszó</label>
    <input type="password"
           name="password"
           id="password"
           class="form-control"
           required
           minlength="8"
           maxlength="36"
           pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,36}$">
    <small class="form-text text-muted">
        Legalább 8, legfeljebb 36 karakter. Tartalmazzon betűt és számot.
    </small>
</div>

        {{-- 🔐 Jelszó megerősítése --}}
        <div class="mb-3">
    <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
    <input type="password"
           name="password_confirmation"
           id="password_confirmation"
           class="form-control"
           required>
    <small class="form-text text-muted">
        Kérjük, ismételd meg az új jelszót pontosan.
    </small>
</div>

        {{-- 💾 Mentés gomb --}}
        <div class="mt-3">
    <button type="submit" class="btn btn-success">
        ✅ Mentés
    </button>
</div>
    </form>
</div>
@endsection
