@extends('layouts_public.app')

@section('title', 'Home • SeFashion')

@section('content')
{{-- HERO FULL IMAGE + OVERLAY TEXT + CENTERED NAV --}}
<section class="relative h-[82vh] min-h-[560px] w-full">
  {{-- background image full --}}
  <div
    class="absolute inset-0 bg-center bg-cover"
    style="background-image:url('{{ asset('images/hero-rack.jpg') }}');">
  </div>

  {{-- dark gradient biar teks kebaca --}}
  <div class="absolute inset-0 bg-gradient-to-r from-black/35 via-black/20 to-transparent"></div>



  {{-- TEXT BOX di atas gambar --}}
  <div class="relative z-10 h-full flex items-center">
    <div class="px-6 sm:px-10 lg:px-16">
      <div class="max-w-xl">
        <h1 class="text-white text-5xl sm:text-6xl font-extrabold leading-tight">
          Style Without<br>Limits
        </h1>

        <p class="mt-5 text-white/85 text-lg">
          Koleksi terbaru, cutting rapi, gampang mix-and-match. Nanti kamu bingung, bukan baju yang salah.
        </p>

        <div class="mt-8">
          <a href="{{ route('shop') }}"
             class="inline-flex items-center gap-2 px-7 py-3.5 bg-white text-gray-900 rounded-xl hover:bg-gray-100">
            Shop Now
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- FEATURE STRIP (2 model) --}}
<section class="py-10 sm:py-14">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid md:grid-cols-2 gap-6">
      <div class="bg-gray-100 rounded-2xl overflow-hidden">
        <div class="grid grid-cols-2 gap-0">
          <img src="{{ asset('images/feat-left-1.jpg') }}" class="w-full h-full object-cover" alt="">
          <img src="{{ asset('images/feat-left-2.jpg') }}" class="w-full h-full object-cover hidden sm:block" alt="">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold">UT Demon Slayer</h3>
          <a href="{{ route('shop') }}" class="text-gray-600 hover:text-gray-900 underline">View More</a>
        </div>
      </div>

      <div class="bg-gray-100 rounded-2xl overflow-hidden">
        <div class="grid grid-cols-2 gap-0">
          <img src="{{ asset('images/feat-right-1.jpg') }}" class="w-full h-full object-cover" alt="">
          <img src="{{ asset('images/feat-right-2.jpg') }}" class="w-full h-full object-cover hidden sm:block" alt="">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold">UT Spy x Family</h3>
          <a href="{{ route('shop') }}" class="text-gray-600 hover:text-gray-900 underline">View More</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- TOP PICKS --}}
<section class="py-10 sm:py-14 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold">Top Picks For You</h2>
      <p class="mt-2 text-gray-600">Hot drops minggu ini. Stok terbatas, jangan kebanyakan mikir.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse($products as $product)
        <a href="{{ route('product.show', $product->id_produk) }}"
           class="group bg-gray-50 rounded-2xl overflow-hidden hover:shadow-card transition">
          <div class="aspect-[3/4] bg-white">
            @php
              $img = $product->gambar ? asset('storage/produk/'.$product->gambar) : asset('images/placeholder-product.jpg');
            @endphp
            <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition" alt="{{ $product->nama_produk }}">
          </div>
          <div class="p-4">
            <h3 class="font-semibold line-clamp-1">{{ $product->nama_produk }}</h3>
            <p class="text-sm text-gray-500 line-clamp-1">{{ $product->kategori }}</p>
            <p class="mt-1 font-semibold">
              Rp {{ number_format($product->detailUkuran->min('harga') ?? 0, 0, ',', '.') }}
            </p>
          </div>
        </a>
      @empty
        {{-- fallback 4 kartu dummy --}}
        @for($i=0; $i<4; $i++)
          <div class="bg-gray-50 rounded-2xl overflow-hidden">
            <div class="aspect-[3/4] bg-gray-200"></div>
            <div class="p-4">
              <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
              <div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div>
              <div class="h-4 bg-gray-200 rounded w-1/3"></div>
            </div>
          </div>
        @endfor
      @endforelse
    </div>

    <div class="mt-8 text-center">
      <a href="{{ route('shop') }}" class="inline-block px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50">
        View More
      </a>
    </div>
  </div>
</section>

{{-- SPLIT BANNER (polo) --}}
<section class="py-10 sm:py-14">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-2 gap-6 items-stretch">
      <div class="rounded-2xl overflow-hidden">
        <img src="{{ asset('images/polo-banner.jpg') }}" class="w-full h-full object-cover" alt="">
      </div>
      <div class="rounded-2xl bg-amber-50 p-8 sm:p-12 flex items-center">
        <div>
          <span class="text-sm uppercase tracking-wider text-gray-500">New Arrivals</span>
          <h3 class="mt-2 text-3xl sm:text-4xl font-extrabold">AIRism Katun Polo</h3>
          <p class="mt-3 text-gray-600">Ringan, adem, dan selalu rapi. Cocok dipakai nongkrong atau ngoding ngebut.</p>
          <a href="{{ route('shop') }}" class="mt-6 inline-block px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black">
            Order Now
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- INSTAGRAM / NEWSLETTER --}}
<section class="py-12 sm:py-16 bg-white">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
    <h3 class="text-2xl sm:text-3xl font-extrabold">Our Instagram</h3>
    <p class="mt-2 text-gray-600">follow our style di @sefashion.id</p>
    <form class="mt-6 max-w-md mx-auto flex gap-3">
      <input type="email" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
             placeholder="Enter your email">
      <button type="button" class="px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black">SIGN UP</button>
    </form>
  </div>
</section>
@endsection
