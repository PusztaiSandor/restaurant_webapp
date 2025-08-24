@extends('layout')

@section('content')
  <!-- Bevezető szakasz -->
<section class="container mx-auto px-4 py-16 text-center">
  <h1 class="text-4xl font-semibold mb-6 text-gray-800">Kapcsolat</h1>
  <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
    Ez a webalkalmazás oktatási céllal készült a Budapesti Műszaki Szakképzési Centrum Verebély László Technikum diákjainak vizsgaremekeként.
  </p>
  <p class="text-xl font-semibold text-amber-700 mt-4">
    Az <span class="font-bold">Esszencia</span> az ízek és az oktatás esszenciája.
  </p>
</section>

  <!-- Elérhetőség és térkép -->
  <section class="container mx-auto px-4 py-8 text-center">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Elérhetőség</h2>
    <p class="text-lg text-gray-700">Budapesti Műszaki Szakképzési Centrum Verebély László Technikum</p>
    <p class="text-lg text-gray-700 mb-6">1139 Budapest, Üteg utca 13–15.</p>
    <div class="w-full max-w-4xl mx-auto mb-8">
      <iframe src="https://www.google.com/maps?q=1139+Budapest,+Üteg+utca+13-15&output=embed"
              width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
  </section>

  <!-- Készítők bemutatása -->
  <section class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">A készítők</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach ([
        ['name' => 'Győri Hajnal Csillag', 'image' => 'gyori.jpg', 'role' => 'UX/UI tervezés és felhasználói élmény tesztelés'],
        ['name' => 'Lednyiczki Richárd', 'image' => 'lednyiczki.jpg', 'role' => 'Frontend fejlesztés és UI logika tesztelés'],
        ['name' => 'Pusztai Sándor', 'image' => 'pusztai.jpg', 'role' => 'Backend fejlesztés és adatbázis tervezés, integrációk tesztelése']
      ] as $creator)
        <div class="text-center">
         <img src="{{ asset('assets/images/keszitok/' . $creator['image']) }}"
     alt="{{ $creator['name'] }}"
     class="creator-image mx-auto mb-4">
          <h3 class="text-xl font-semibold text-gray-800">{{ $creator['name'] }}</h3>
          <p class="text-gray-600 mt-2">{{ $creator['role'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  <!-- Tanári köszönet -->
<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Köszönet az oktatóknak</h2>
  <p class="text-center text-gray-700 max-w-3xl mx-auto mb-6">
    Köszönjük mindazoknak, akik szakmai tudásukkal, türelmükkel és útmutatásukkal támogatták munkánkat!
  </p>

  <div class="overflow-x-auto">
    <table class="table-auto mx-auto w-full max-w-4xl text-left">
      <tbody class="text-gray-700">
        <tr>
          <td class="px-4 py-2 font-semibold">Baranyi Péter</td>
          <td class="px-4 py-2">Informatikai és távközlési alapok I–II., IKT projektmunka I., Adatbázis-kezelés I., Szoftvertesztelés</td>
        </tr>
        <tr>
          <td class="px-4 py-2 font-semibold">Faludi Anita</td>
          <td class="px-4 py-2">Programozási alapok, Webprogramozás, Adatbázis-kezelés II., Frontend</td>
        </tr>
        <tr>
          <td class="px-4 py-2 font-semibold">Fertály Zita</td>
          <td class="px-4 py-2">Szakmai angol</td>
        </tr>
        <tr>
          <td class="px-4 py-2 font-semibold">Horváth Attila</td>
          <td class="px-4 py-2">Backend</td>
        </tr>
        <tr>
          <td class="px-4 py-2 font-semibold">Juhász Zoltán</td>
          <td class="px-4 py-2">Asztali és mobil alkalmazások fejlesztése és tesztelése, IKT projektmunka II.</td>
        </tr>
        <tr>
          <td class="px-4 py-2 font-semibold">Somogyi Erika</td>
          <td class="px-4 py-2">Asztali alkalmazások fejlesztése</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

  <!-- Általános Felhasználási Szabályok -->
  <section class="container mx-auto px-4 py-8 text-center">
    <a href="{{ route('terms') }}" class="text-amber-700 hover:underline text-lg">Általános Felhasználási Feltételek megtekintése</a>
  </section>

  <!-- Üzenetküldés és értékelés -->
<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Értékelés és visszajelzés</h2>



  @if(auth()->check() && auth()->user()->role === 'user')
    <form method="POST" action="{{ route('contact.send') }}" class="mx-auto" style="max-width:600px;">
        @csrf
        {{-- 📌 Típusválasztó --}}
        <div class="mb-3">
            <label for="type" class="form-label">Visszajelzés típusa</label>
            <select name="type" id="type" class="form-select" required>
                <option value="" disabled selected>– Válassz –</option>
                <option value="message">Üzenet az étteremnek</option>
                <option value="rating">Étterem értékelése</option>
            </select>
        </div>

        {{-- ⭐ Csillagos értékelés – csak ha type = rating --}}
        <div class="mb-3" id="rating-block" style="display:none;">
            <label for="rating" class="form-label">Értékelés (1–5 csillag)</label>
            <select name="rating" id="rating" class="form-select">
                <option value="" disabled selected>– Válassz –</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} csillag</option>
                @endfor
            </select>
        </div>

        {{-- 📝 Tárgy --}}
        <div class="mb-3">
            <label for="subject" class="form-label">Tárgy (opcionális)</label>
            <input type="text" id="subject" name="subject" class="form-control" placeholder="Pl. Kérdés, javaslat, dicséret">
        </div>

        {{-- 💬 Tartalom --}}
        <div class="mb-3">
            <label for="content" class="form-label">Üzenet / Vélemény</label>
            <textarea id="content" name="content" class="form-control" rows="5" placeholder="Írd meg az üzeneted vagy értékelésed" required></textarea>
        </div>

        <button type="submit" class="btn btn-dark w-100">Küldés</button>
    </form>

    {{-- 🔧 Dinamikus megjelenítés JS --}}
    <script>
        document.getElementById('type').addEventListener('change', function () {
            const ratingBlock = document.getElementById('rating-block');
            ratingBlock.style.display = this.value === 'rating' ? 'block' : 'none';
        });
    </script>
@else
    <p class="text-center text-gray-700">
        Az értékeléshez és visszajelzéshez kérlek
        <a href="{{ route('login') }}" class="text-amber-700 hover:underline">jelentkezz be</a>.
    </p>
@endif


</section>
@endsection
