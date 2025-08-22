@extends('layout')

@section('content')
<section class="container py-5 text-center">
    <h1 class="mb-4 fw-bold">Általános Felhasználási Feltételek</h1>
    <p class="lead mb-5">
        Ez a webalkalmazás oktatási céllal készült a
        <strong>Budapesti Műszaki Szakképzési Centrum Verebély László Technikum</strong>
        diákjainak <em>technikusi vizsgaremek</em> projektjeként.
    </p>

    <div class="text-start mx-auto" style="max-width: 900px;">
        <h5 class="mb-3">1. A webalkalmazás célja</h5>
        <p>
            Az Esszencia Étterem webalkalmazás kizárólag tanulmányi céllal készült, nem kereskedelmi használatra. Az oldal célja,
            hogy bemutassa a szoftverfejlesztő és -tesztelő technikus képzésen megszerzett gyakorlati ismereteket.
        </p>

        <h5 class="mt-4 mb-3">2. Szerzői jogi védelem</h5>
        <p>
            Az alkalmazás forráskódja, struktúrája, dizájnja és tartalma a készítők szellemi tulajdonát képezik. A kódok
            felhasználása csak <strong>előzetes írásos engedéllyel</strong> történhet.
        </p>
        <p>
            Kivételt képeznek a <strong>Budapesti Műszaki Szakképzési Centrum Verebély László Technikum</strong> oktatói,
            akik oktatási célból <strong>korlátozások nélkül</strong> használhatják az alkalmazást és annak forráskódját.
        </p>

        <h5 class="mt-4 mb-3">3. Felelősség kizárása</h5>
        <p>
            A készítők nem vállalnak felelősséget a kódok felhasználásából eredő működési, jogi vagy technikai problémákért.
            Minden felhasználó saját felelősségére használja az alkalmazást vagy annak elemeit.
        </p>

        <h5 class="mt-4 mb-3">4. Adatvédelem</h5>
        <p>
            Az oldal nem gyűjt vagy tárol személyes adatokat kereskedelmi céllal. Minden adatkezelés csak az oktatási
            működéshez szükséges mértékben történik. Az alkalmazás nem küld ki marketing célú üzeneteket.
        </p>

        <h5 class="mt-4 mb-3">5. Verzió és elérhetőség</h5>
        <p>
            Az oldal fejlesztése 2025-ben történt, statikus és tantermi demonstrációs célokra. Nem garantált a hosszú távú működés vagy támogatás.
        </p>
    </div>

    <div class="mt-5">
        <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
            ← Vissza a Kapcsolat oldalra
        </a>
    </div>
</section>
@endsection
