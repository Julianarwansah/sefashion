@extends('layouts_public.app')

@section('title', 'Home • SeFashion')

@section('content')
{{-- HERO MODERN SPLIT LAYOUT --}}
<section class="relative min-h-screen w-full overflow-hidden bg-gradient-to-br from-gray-50 via-white to-gray-100">
  {{-- Animated Background Shapes --}}
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-20 left-10 w-72 h-72 bg-gradient-to-br from-gray-900/5 to-transparent rounded-full blur-3xl animate-blob"></div>
    <div class="absolute top-40 right-20 w-96 h-96 bg-gradient-to-br from-gray-900/5 to-transparent rounded-full blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-32 left-1/3 w-80 h-80 bg-gradient-to-br from-gray-900/5 to-transparent rounded-full blur-3xl animate-blob animation-delay-4000"></div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center min-h-screen py-16 lg:py-0">

      {{-- Left Content --}}
      <div class="relative order-2 lg:order-1 space-y-6 sm:space-y-8">
        {{-- Badge with Animation --}}
        <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-900 to-gray-700 text-white rounded-full text-xs sm:text-sm font-semibold shadow-lg opacity-0 animate-slide-up" style="animation-delay: 0.1s; animation-fill-mode: forwards;">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
          </span>
          New Collection 2025
        </div>

        {{-- Main Heading with Stagger Animation --}}
        <div class="space-y-4">
          <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-[0.95] text-gray-900 opacity-0 animate-slide-up" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
            Elevate
          </h1>
          <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-[0.95] opacity-0 animate-slide-up" style="animation-delay: 0.3s; animation-fill-mode: forwards;">
            <span class="bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900 bg-clip-text text-transparent inline-block">
              Your Style
            </span>
          </h1>
          <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-[0.95] text-gray-900 opacity-0 animate-slide-up" style="animation-delay: 0.4s; animation-fill-mode: forwards;">
            Game
          </h1>
        </div>

        {{-- Description --}}
        <p class="text-base sm:text-lg lg:text-xl text-gray-600 leading-relaxed max-w-xl opacity-0 animate-slide-up" style="animation-delay: 0.5s; animation-fill-mode: forwards;">
          Discover premium fashion that defines your personality. Curated collections for the modern trendsetter who dares to be different.
        </p>

        {{-- CTA Buttons with Hover Effects --}}
        <div class="flex flex-col sm:flex-row gap-4 opacity-0 animate-slide-up" style="animation-delay: 0.6s; animation-fill-mode: forwards;">
          <a href="{{ route('shop') }}"
             class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 text-base font-bold">
            <span class="relative z-10 flex items-center gap-3">
              Explore Collection
              <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-black transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
          </a>

          <a href="{{ route('about') }}"
             class="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border-2 border-gray-900 text-gray-900 rounded-2xl transition-all duration-300 hover:bg-gray-900 hover:text-white hover:-translate-y-1 hover:shadow-xl text-base font-bold">
            Learn More
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
        </div>

        {{-- Stats with Count-up Animation --}}
        <div class="grid grid-cols-3 gap-6 pt-8 border-t-2 border-gray-200 opacity-0 animate-slide-up" style="animation-delay: 0.7s; animation-fill-mode: forwards;">
          <div class="group">
            <div class="text-3xl sm:text-4xl font-black text-gray-900 mb-1 transition-transform duration-300 group-hover:scale-110">10k+</div>
            <div class="text-xs sm:text-sm text-gray-600 font-medium">Happy Customers</div>
          </div>
          <div class="group">
            <div class="text-3xl sm:text-4xl font-black text-gray-900 mb-1 transition-transform duration-300 group-hover:scale-110">500+</div>
            <div class="text-xs sm:text-sm text-gray-600 font-medium">Products</div>
          </div>
          <div class="group">
            <div class="text-3xl sm:text-4xl font-black text-gray-900 mb-1 transition-transform duration-300 group-hover:scale-110">4.9★</div>
            <div class="text-xs sm:text-sm text-gray-600 font-medium">Rating</div>
          </div>
        </div>
      </div>

      {{-- Right Image Section with Parallax --}}
      <div class="relative order-1 lg:order-2 opacity-0 animate-fade-in" style="animation-delay: 0.4s; animation-fill-mode: forwards;">
        {{-- Main Image Container --}}
        <div class="relative aspect-square max-w-xs sm:max-w-sm mx-auto" id="heroImageContainer">
          {{-- Animated Background Ring --}}
          <div class="absolute inset-0 rounded-[2.5rem] border-4 border-gray-900/10 animate-pulse-slow"></div>

          {{-- Background Glow Effect --}}
          <div class="absolute -inset-4 bg-gradient-to-r from-gray-900/20 via-gray-700/20 to-gray-900/20 rounded-[3rem] blur-2xl animate-glow"></div>

          {{-- Main Hero Image --}}
          <div class="relative z-10 w-full h-full rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-transform duration-700 hover:scale-[1.02]">
            <img
              src="{{ asset('images/hero-rack.jpg') }}"
              alt="Fashion Hero"
              class="w-full h-full object-cover transform transition-transform duration-1000 hover:scale-110"
            >
            {{-- Overlay with Gradient --}}
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/30 via-transparent to-transparent"></div>

            {{-- Shine Effect on Hover --}}
            <div class="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-500">
              <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 animate-shine"></div>
            </div>
          </div>

          {{-- Floating Product Card with Enhanced Animation --}}
          <div class="absolute -bottom-6 -left-6 lg:-left-12 bg-white rounded-2xl shadow-2xl p-5 max-w-xs hidden md:block transform transition-all duration-300 hover:scale-105 hover:shadow-3xl animate-float-smooth" style="animation-delay: 0.8s;">
            <div class="flex items-center gap-4">
              <div class="relative w-16 h-16 flex-shrink-0 group">
                <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-gray-700 rounded-xl blur opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&h=100&fit=crop" alt="Product" class="relative w-full h-full object-cover rounded-xl">
              </div>
              <div>
                <p class="text-sm font-bold text-gray-900">Premium Watch</p>
                <p class="text-xs text-gray-500 mb-1">Starting from</p>
                <p class="text-xl font-black text-gray-900">Rp 299K</p>
              </div>
            </div>
            {{-- Discount Badge --}}
            <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg animate-bounce-slow">
              -30%
            </div>
          </div>

          {{-- Floating Review Badge with Better Animation --}}
          <div class="absolute top-6 right-6 bg-white/95 backdrop-blur-md rounded-2xl px-5 py-3 shadow-xl hidden sm:block transform transition-all duration-300 hover:scale-105 animate-float-smooth" style="animation-delay: 1s; animation-duration: 4s;">
            <div class="flex items-center gap-3">
              <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 border-2 border-white shadow-sm"></div>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 border-2 border-white shadow-sm"></div>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 border-2 border-white shadow-sm"></div>
              </div>
              <div>
                <p class="text-xs font-bold text-gray-900">2.5k+ Reviews</p>
                <div class="flex gap-0.5 mt-0.5">
                  <span class="text-yellow-400 text-xs">★★★★★</span>
                </div>
              </div>
            </div>
          </div>

          {{-- Trending Badge --}}
          <div class="absolute top-1/3 -right-4 bg-gradient-to-r from-gray-900 to-gray-700 text-white px-4 py-2 rounded-l-full shadow-xl hidden lg:block animate-slide-left" style="animation-delay: 1.2s;">
            <p class="text-xs font-bold tracking-wider">🔥 TRENDING</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Scroll Indicator with Better Animation --}}
  <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 hidden lg:flex flex-col items-center gap-2 opacity-0 animate-fade-in" style="animation-delay: 1.5s; animation-fill-mode: forwards;">
    <span class="text-xs text-gray-500 font-semibold tracking-wider uppercase">Scroll Down</span>
    <div class="w-6 h-10 border-2 border-gray-400 rounded-full flex justify-center pt-2 animate-bounce-slow">
      <div class="w-1 h-2 bg-gray-600 rounded-full animate-scroll-indicator"></div>
    </div>
  </div>
</section>


{{-- FEATURE STRIP (2 model) --}}
<section class="py-10 sm:py-14">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid md:grid-cols-2 gap-6">
      <div class="bg-gray-100 rounded-2xl overflow-hidden scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
        <div class="grid grid-cols-2 gap-0">
          <img src="{{ asset('images/feat-left-1.jpg') }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" alt="">
          <img src="{{ asset('images/feat-left-2.jpg') }}" class="w-full h-full object-cover hidden sm:block hover:scale-110 transition-transform duration-500" alt="">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold">UT Demon Slayer</h3>
          <a href="{{ route('shop') }}" class="text-gray-600 hover:text-gray-900 underline inline-flex items-center gap-1 group">
            View More
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
        </div>
      </div>

      <div class="bg-gray-100 rounded-2xl overflow-hidden scroll-reveal opacity-0 translate-y-8 transition-all duration-700 delay-100">
        <div class="grid grid-cols-2 gap-0">
          <img src="{{ asset('images/feat-right-1.jpg') }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" alt="">
          <img src="{{ asset('images/feat-right-2.jpg') }}" class="w-full h-full object-cover hidden sm:block hover:scale-110 transition-transform duration-500" alt="">
        </div>
        <div class="p-6">
          <h3 class="text-xl font-semibold">UT Spy x Family</h3>
          <a href="{{ route('shop') }}" class="text-gray-600 hover:text-gray-900 underline inline-flex items-center gap-1 group">
            View More
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- TOP PICKS --}}
<section class="py-10 sm:py-14 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
      <h2 class="text-2xl sm:text-3xl font-extrabold">Top Picks For You</h2>
      <p class="mt-2 text-gray-600">Hot drops minggu ini. Stok terbatas, jangan kebanyakan mikir.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse($products as $product)
        <a href="{{ route('product.show', $product->id_produk) }}"
           class="group bg-gray-50 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 scroll-reveal opacity-0 translate-y-8">
          <div class="aspect-[3/4] bg-white overflow-hidden">
            <img src="{{ $product->gambar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $product->nama_produk }}">
          </div>
          <div class="p-4">
            <h3 class="font-semibold line-clamp-1 group-hover:text-gray-900 transition">{{ $product->nama_produk }}</h3>
            <p class="text-sm text-gray-500 line-clamp-1">{{ $product->kategori }}</p>
            <p class="mt-1 font-semibold text-gray-900">
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

{{-- FEATURED CATEGORIES --}}
<section class="py-10 sm:py-14 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
      <h2 class="text-2xl sm:text-3xl font-extrabold">Shop by Category</h2>
      <p class="mt-2 text-gray-600">Temukan style yang sesuai dengan kepribadianmu</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
      {{-- Men --}}
      <a href="{{ route('shop', ['category' => 'Men']) }}" class="group relative overflow-hidden rounded-2xl aspect-[3/4] scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
        <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=400&h=600&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Men">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 text-white">
          <h3 class="text-xl sm:text-2xl font-bold mb-1">Men</h3>
          <p class="text-xs sm:text-sm text-white/80">Casual & Formal</p>
        </div>
      </a>

      {{-- Women --}}
      <a href="{{ route('shop', ['category' => 'Women']) }}" class="group relative overflow-hidden rounded-2xl aspect-[3/4] scroll-reveal opacity-0 translate-y-8 transition-all duration-700 delay-100">
        <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=400&h=600&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Women">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 text-white">
          <h3 class="text-xl sm:text-2xl font-bold mb-1">Women</h3>
          <p class="text-xs sm:text-sm text-white/80">Trendy & Chic</p>
        </div>
      </a>

      {{-- Kids --}}
      <a href="{{ route('shop', ['category' => 'Kids']) }}" class="group relative overflow-hidden rounded-2xl aspect-[3/4] scroll-reveal opacity-0 translate-y-8 transition-all duration-700 delay-200">
        <img src="https://images.unsplash.com/photo-1503944583220-79d8926ad5e2?w=400&h=600&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Kids">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 text-white">
          <h3 class="text-xl sm:text-2xl font-bold mb-1">Kids</h3>
          <p class="text-xs sm:text-sm text-white/80">Cute & Comfy</p>
        </div>
      </a>

      {{-- Accessories --}}
      <a href="{{ route('shop', ['category' => 'Accessories']) }}" class="group relative overflow-hidden rounded-2xl aspect-[3/4] scroll-reveal opacity-0 translate-y-8 transition-all duration-700 delay-300">
        <img src="https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?w=400&h=600&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Accessories">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 text-white">
          <h3 class="text-xl sm:text-2xl font-bold mb-1">Accessories</h3>
          <p class="text-xs sm:text-sm text-white/80">Complete Your Look</p>
        </div>
      </a>
    </div>
  </div>
</section>

{{-- SPLIT BANNER (polo) --}}
<section class="py-10 sm:py-14">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-2 gap-6 items-stretch">
      <div class="rounded-2xl overflow-hidden scroll-reveal opacity-0 -translate-x-8 transition-all duration-700">
        <img src="{{ asset('images/polo-banner.jpg') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" alt="">
      </div>
      <div class="rounded-2xl bg-amber-50 p-8 sm:p-12 flex items-center scroll-reveal opacity-0 translate-x-8 transition-all duration-700">
        <div>
          <span class="text-sm uppercase tracking-wider text-gray-500">New Arrivals</span>
          <h3 class="mt-2 text-3xl sm:text-4xl font-extrabold">AIRism Katun Polo</h3>
          <p class="mt-3 text-gray-600">Ringan, adem, dan selalu rapi. Cocok dipakai nongkrong atau ngoding ngebut.</p>
          <a href="{{ route('shop') }}" class="mt-6 inline-block px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black hover:scale-105 transition-all duration-300">
            Order Now
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- WHY CHOOSE US --}}
<section class="py-10 sm:py-14 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
      <h2 class="text-2xl sm:text-3xl font-extrabold">Why Choose SeFashion?</h2>
      <p class="mt-2 text-gray-600">Alasan kenapa kamu harus belanja di sini</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
      {{-- Free Shipping --}}
      <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all duration-300 scroll-reveal opacity-0 translate-y-8">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-900 rounded-2xl flex items-center justify-center transform hover:rotate-6 transition-transform duration-300">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">Free Shipping</h3>
        <p class="text-sm text-gray-600">Gratis ongkir untuk pembelian di atas Rp 200.000</p>
      </div>

      {{-- Easy Returns --}}
      <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all duration-300 scroll-reveal opacity-0 translate-y-8 delay-100">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-900 rounded-2xl flex items-center justify-center transform hover:rotate-6 transition-transform duration-300">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">Easy Returns</h3>
        <p class="text-sm text-gray-600">Tukar/return dalam 7 hari jika ada masalah</p>
      </div>

      {{-- Secure Payment --}}
      <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all duration-300 scroll-reveal opacity-0 translate-y-8 delay-200">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-900 rounded-2xl flex items-center justify-center transform hover:rotate-6 transition-transform duration-300">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">Secure Payment</h3>
        <p class="text-sm text-gray-600">Pembayaran aman dengan berbagai metode</p>
      </div>

      {{-- 24/7 Support --}}
      <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-all duration-300 scroll-reveal opacity-0 translate-y-8 delay-300">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-900 rounded-2xl flex items-center justify-center transform hover:rotate-6 transition-transform duration-300">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">24/7 Support</h3>
        <p class="text-sm text-gray-600">CS siap bantu kamu kapan aja via chat</p>
      </div>
    </div>
  </div>
</section>

{{-- TESTIMONIALS --}}
<section class="py-10 sm:py-14 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
      <h2 class="text-2xl sm:text-3xl font-extrabold">What Our Customers Say</h2>
      <p class="mt-2 text-gray-600">Testimoni dari pelanggan yang puas</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      {{-- Testimonial 1 --}}
      <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 scroll-reveal opacity-0 translate-y-8">
        <div class="flex gap-1 mb-4">
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <p class="text-gray-700 mb-4 italic">"Bajunya bagus banget, bahannya nyaman dan sesuai ekspektasi. Pengiriman juga cepat!"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
          <div>
            <p class="font-semibold text-sm">Rina Wati</p>
            <p class="text-xs text-gray-500">Jakarta</p>
          </div>
        </div>
      </div>

      {{-- Testimonial 2 --}}
      <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 scroll-reveal opacity-0 translate-y-8 delay-100">
        <div class="flex gap-1 mb-4">
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <p class="text-gray-700 mb-4 italic">"Harga terjangkau tapi kualitas oke punya. Recommended buat yang cari baju berkualitas!"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
          <div>
            <p class="font-semibold text-sm">Budi Santoso</p>
            <p class="text-xs text-gray-500">Bandung</p>
          </div>
        </div>
      </div>

      {{-- Testimonial 3 --}}
      <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 scroll-reveal opacity-0 translate-y-8 delay-200">
        <div class="flex gap-1 mb-4">
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <p class="text-gray-700 mb-4 italic">"Pelayanan ramah, produk sesuai deskripsi. Pasti balik lagi belanja di sini!"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
          <div>
            <p class="font-semibold text-sm">Siti Nurhaliza</p>
            <p class="text-xs text-gray-500">Surabaya</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- STATS / NUMBERS SECTION --}}
<section class="py-10 sm:py-16 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8 sm:mb-12 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white">Our Achievement</h2>
      <p class="mt-3 sm:mt-4 text-base sm:text-lg text-gray-300 max-w-2xl mx-auto">Numbers that speak for our commitment to quality and customer satisfaction</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
      {{-- Stat 1 --}}
      <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
        <div class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-2">
          <span class="counter" data-target="10000">0</span>+
        </div>
        <p class="text-sm sm:text-base text-gray-300 font-medium">Happy Customers</p>
      </div>

      {{-- Stat 2 --}}
      <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 scroll-reveal opacity-0 translate-y-8 delay-100 transition-all duration-700">
        <div class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-2">
          <span class="counter" data-target="5000">0</span>+
        </div>
        <p class="text-sm sm:text-base text-gray-300 font-medium">Products Sold</p>
      </div>

      {{-- Stat 3 --}}
      <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 scroll-reveal opacity-0 translate-y-8 delay-200 transition-all duration-700">
        <div class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-2">
          <span class="counter" data-target="50">0</span>+
        </div>
        <p class="text-sm sm:text-base text-gray-300 font-medium">Cities Reached</p>
      </div>

      {{-- Stat 4 --}}
      <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 scroll-reveal opacity-0 translate-y-8 delay-300 transition-all duration-700">
        <div class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-2">
          <span class="counter" data-target="98">0</span>%
        </div>
        <p class="text-sm sm:text-base text-gray-300 font-medium">Satisfaction Rate</p>
      </div>
    </div>
  </div>
</section>

{{-- FAQ SECTION --}}
<section class="py-10 sm:py-16 bg-gray-50">
  <div class="max-w-4xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8 sm:mb-12 scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900">Frequently Asked Questions</h2>
      <p class="mt-3 sm:mt-4 text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">Got questions? We've got answers!</p>
    </div>

    <div class="space-y-4">
      {{-- FAQ Item 1 --}}
      <div class="faq-item bg-white rounded-2xl shadow-md overflow-hidden scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
        <button class="faq-question w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
          <span class="text-base sm:text-lg font-semibold text-gray-900 pr-4">Bagaimana cara melakukan pemesanan?</span>
          <svg class="faq-icon w-6 h-6 text-gray-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="px-6 pb-5 text-sm sm:text-base text-gray-600 leading-relaxed">
            Pilih produk yang Anda inginkan, klik "Add to Cart", lalu lanjutkan ke halaman keranjang. Setelah itu klik "Checkout" dan lengkapi data pengiriman Anda. Kami akan menghubungi Anda untuk konfirmasi pesanan.
          </div>
        </div>
      </div>

      {{-- FAQ Item 2 --}}
      <div class="faq-item bg-white rounded-2xl shadow-md overflow-hidden scroll-reveal opacity-0 translate-y-8 delay-100 transition-all duration-700">
        <button class="faq-question w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
          <span class="text-base sm:text-lg font-semibold text-gray-900 pr-4">Apakah ada garansi untuk produk yang dijual?</span>
          <svg class="faq-icon w-6 h-6 text-gray-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="px-6 pb-5 text-sm sm:text-base text-gray-600 leading-relaxed">
            Ya, semua produk kami memiliki garansi kualitas. Jika ada cacat produksi atau kerusakan saat pengiriman, kami akan melakukan penukaran atau pengembalian dana sesuai kebijakan yang berlaku.
          </div>
        </div>
      </div>

      {{-- FAQ Item 3 --}}
      <div class="faq-item bg-white rounded-2xl shadow-md overflow-hidden scroll-reveal opacity-0 translate-y-8 delay-200 transition-all duration-700">
        <button class="faq-question w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
          <span class="text-base sm:text-lg font-semibold text-gray-900 pr-4">Berapa lama estimasi pengiriman?</span>
          <svg class="faq-icon w-6 h-6 text-gray-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="px-6 pb-5 text-sm sm:text-base text-gray-600 leading-relaxed">
            Estimasi pengiriman bervariasi tergantung lokasi. Untuk wilayah Jabodetabek sekitar 2-3 hari kerja, sedangkan untuk luar kota 3-7 hari kerja. Anda akan mendapatkan nomor resi untuk tracking paket.
          </div>
        </div>
      </div>

      {{-- FAQ Item 4 --}}
      <div class="faq-item bg-white rounded-2xl shadow-md overflow-hidden scroll-reveal opacity-0 translate-y-8 delay-300 transition-all duration-700">
        <button class="faq-question w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
          <span class="text-base sm:text-lg font-semibold text-gray-900 pr-4">Apakah bisa melakukan retur atau penukaran?</span>
          <svg class="faq-icon w-6 h-6 text-gray-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="px-6 pb-5 text-sm sm:text-base text-gray-600 leading-relaxed">
            Ya, kami menerima retur atau penukaran dalam waktu 7 hari setelah produk diterima. Produk harus dalam kondisi belum dipakai, masih dengan tag, dan kemasan asli. Hubungi customer service kami untuk prosesnya.
          </div>
        </div>
      </div>

      {{-- FAQ Item 5 --}}
      <div class="faq-item bg-white rounded-2xl shadow-md overflow-hidden scroll-reveal opacity-0 translate-y-8 delay-400 transition-all duration-700">
        <button class="faq-question w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors duration-200">
          <span class="text-base sm:text-lg font-semibold text-gray-900 pr-4">Metode pembayaran apa saja yang diterima?</span>
          <svg class="faq-icon w-6 h-6 text-gray-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="px-6 pb-5 text-sm sm:text-base text-gray-600 leading-relaxed">
            Kami menerima berbagai metode pembayaran termasuk transfer bank (BCA, Mandiri, BNI), e-wallet (GoPay, OVO, Dana), dan COD (Cash on Delivery) untuk area tertentu. Pilih metode yang paling nyaman untuk Anda.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- INSTAGRAM / NEWSLETTER --}}
<section class="py-12 sm:py-16 bg-white">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center scroll-reveal opacity-0 translate-y-8 transition-all duration-700">
    <h3 class="text-2xl sm:text-3xl font-extrabold">Our Instagram</h3>
    <p class="mt-2 text-gray-600 text-sm sm:text-base">follow our style di @sefashion.id</p>
    <form class="mt-6 max-w-md mx-auto flex flex-col sm:flex-row gap-3">
      <input type="email" class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-900/10 transition text-sm sm:text-base"
             placeholder="Enter your email">
      <button type="button" class="px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black hover:scale-105 transition-all duration-300 whitespace-nowrap text-sm sm:text-base font-medium">SIGN UP</button>
    </form>
  </div>
</section>

@push('styles')
<style>
/* Enhanced Animation keyframes */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideLeft {
  from {
    opacity: 0;
    transform: translateX(40px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px) scale(1);
  }
  50% {
    transform: translateY(-20px) scale(1.05);
  }
}

@keyframes floatDelayed {
  0%, 100% {
    transform: translateY(0px) scale(1);
  }
  50% {
    transform: translateY(-30px) scale(1.1);
  }
}

@keyframes floatSmooth {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-12px);
  }
}

@keyframes blob {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
}

@keyframes glow {
  0%, 100% {
    opacity: 0.5;
  }
  50% {
    opacity: 0.8;
  }
}

@keyframes shine {
  0% {
    left: -100%;
  }
  100% {
    left: 200%;
  }
}

@keyframes pulseSlow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.3;
  }
}

@keyframes bounceSlow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes scrollIndicator {
  0% {
    transform: translateY(0);
    opacity: 1;
  }
  100% {
    transform: translateY(16px);
    opacity: 0;
  }
}

/* Animation Classes */
.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
}

.animate-slide-up {
  animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-slide-left {
  animation: slideLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  animation-fill-mode: forwards;
}

.animate-fade-in {
  animation: fadeIn 1s ease-out forwards;
}

.animate-float-smooth {
  animation: floatSmooth 3s ease-in-out infinite;
}

.animate-blob {
  animation: blob 7s infinite;
}

.animate-glow {
  animation: glow 3s ease-in-out infinite;
}

.animate-shine {
  animation: shine 2s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulseSlow 3s ease-in-out infinite;
}

.animate-bounce-slow {
  animation: bounceSlow 2s ease-in-out infinite;
}

.animate-scroll-indicator {
  animation: scrollIndicator 1.5s ease-in-out infinite;
}

/* Animation Delays */
.animation-delay-200 {
  animation-delay: 0.2s;
  opacity: 0;
}

.animation-delay-400 {
  animation-delay: 0.4s;
  opacity: 0;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
  animation: floatDelayed 8s ease-in-out infinite;
  animation-delay: 1s;
}

.scroll-reveal {
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

.scroll-reveal.revealed {
  opacity: 1 !important;
  transform: translateY(0) translateX(0) !important;
}

.delay-100 {
  transition-delay: 100ms;
}
</style>
@endpush

@push('scripts')
<script>
// Parallax effect for hero image
document.addEventListener('scroll', function() {
  const heroImage = document.getElementById('heroImage');
  if (heroImage) {
    const scrolled = window.pageYOffset;
    const rate = scrolled * 0.5;
    heroImage.style.transform = `translateY(${rate}px)`;
  }
});

// Scroll reveal animation
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('revealed');
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);

// Observe all scroll-reveal elements
document.addEventListener('DOMContentLoaded', function() {
  const scrollRevealElements = document.querySelectorAll('.scroll-reveal');
  scrollRevealElements.forEach(el => observer.observe(el));

  // FAQ Accordion functionality
  const faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');
    const icon = item.querySelector('.faq-icon');

    question.addEventListener('click', () => {
      const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

      // Close all other FAQ items
      faqItems.forEach(otherItem => {
        if (otherItem !== item) {
          const otherAnswer = otherItem.querySelector('.faq-answer');
          const otherIcon = otherItem.querySelector('.faq-icon');
          otherAnswer.style.maxHeight = '0px';
          otherIcon.style.transform = 'rotate(0deg)';
        }
      });

      // Toggle current item
      if (isOpen) {
        answer.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
      } else {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
      }
    });
  });

  // Counter animation for stats
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const counter = entry.target;
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
          current += increment;
          if (current < target) {
            counter.textContent = Math.ceil(current).toLocaleString();
            requestAnimationFrame(updateCounter);
          } else {
            counter.textContent = target.toLocaleString();
          }
        };

        updateCounter();
        counterObserver.unobserve(counter);
      }
    });
  }, { threshold: 0.5 });

  // Observe all counter elements
  const counters = document.querySelectorAll('.counter');
  counters.forEach(counter => counterObserver.observe(counter));
});
</script>
@endpush
@endsection
