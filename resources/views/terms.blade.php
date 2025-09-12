@extends('layout')

@section('content')
  <section class="container py-3 text-center">
    <h1 class="mb-5 fw-bold">Általános felhasználási feltételek</h1>

<div class="mx-auto" style="max-width: 900px; text-align: justify;">
      <p class="fs-5 lead mb-5">
        Ez a webalkalmazás oktatási céllal készült a
        <strong>Budapesti Műszaki Szakképzési Centrum Verebély László Technikum</strong>
        diákjainak <em>technikusi vizsgaremek</em> projektjeként.
      </p>
      <h3 class="mb-3">1. A webalkalmazás célja</h3>
      <p class="fs-5">
        Az Esszencia Étterem webalkalmazás kizárólag tanulmányi céllal készült, nem kereskedelmi használatra. Célja,
        hogy bemutassa a szoftverfejlesztő és -tesztelő technikus képzés során megszerzett gyakorlati ismereteket.
      </p>

      <h3 class="mt-4 mb-3">2. Oktatási licenc és szabad felhasználás</h3>
      <p class="fs-5">
        Az alkalmazás forráskódja, struktúrája, dizájnja és tartalma a készítők szellemi tulajdonát képezik.
        <strong>Oktatási célokra</strong> az alkalmazás és annak forráskódja <strong>szabadon, korlátozás
          nélkül</strong> felhasználható bármely oktatási intézmény, tanuló vagy oktató által. A felhasználás nem
        igényel előzetes engedélyt, amennyiben nem történik vele kereskedelmi tevékenység.
      </p>
      <p class="fs-5">
        A forráskód felhasználása során kérjük, hogy a készítők és az eredeti intézmény neve (BMSZC Verebély László
        Technikum)
        maradjon meg a dokumentációban vagy a láblécben hivatkozásként.
      </p>

      <h3 class="mt-4 mb-3">3. Felelősség kizárása</h3>
      <p class="fs-5">
        A készítők nem vállalnak felelősséget a kódok felhasználásából eredő működési, jogi vagy technikai
        problémákért.
        Minden felhasználó saját felelősségére használja az alkalmazást vagy annak elemeit.
      </p>

      <h3 class="mt-4 mb-3">4. Adatvédelem</h3>
      <p class="fs-5">
        Az oldal nem gyűjt és nem tárol személyes adatokat kereskedelmi céllal. Minden adatkezelés kizárólag az
        oktatási
        működéshez szükséges mértékben történik. Az alkalmazás nem küld marketing célú üzeneteket.
      </p>

      <h3 class="mt-4 mb-3">5. Verzió és elérhetőség</h3>
      <p class="fs-5">
        Az oldal fejlesztése 2025-ben történt, statikus és tantermi demonstrációs célokra. Az alkalmazás a Laravel
        keretrendszerben készült, PHP 8.2+ és MySQL/MariaDB adatbázis környezetben működik. A hosszú távú működés
        vagy támogatás nem garantált.
      </p>
    </div>

    <div class="mt-5">
      <a href="{{ route('menu') }}" class="btn btn-outline-secondary">
        Tovább az Étlapra
      </a>
    </div>
  </section>
@endsection

