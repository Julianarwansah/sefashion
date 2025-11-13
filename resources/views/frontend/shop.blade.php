@extends('layouts.app')

@section('title', 'Shop • SeFashion')

@section('content')
{{-- HERO BANNER WITH SHOP TEXT --}}
<section class="relative h-[50vh] min-h-[400px] w-full">
  {{-- background image --}}
  <div
    class="absolute inset-0 bg-center bg-cover"
    style="background-image:url('{{ asset('images/hero-rack.jpg') }}');">
  </div>

  {{-- dark overlay --}}
  <div class="absolute inset-0 bg-black/40"></div>

  {{-- Centered Shop Text --}}
  <div class="relative z-10 h-full flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-white text-5xl sm:text-6xl font-extrabold">
        Shop
      </h1>
    </div>
  </div>
</section>

{{-- BREADCRUMB --}}
<section class="py-4 border-b">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center gap-2 text-sm text-gray-600">
      <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
      <span class="text-gray-900 font-medium">Shop</span>
    </div>
  </div>
</section>

{{-- FILTER & SORT BAR --}}
<section class="py-6 border-b bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      {{-- Filter by Category --}}
      <div class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Category:</label>
        <select onchange="window.location.href = this.value" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10">
          <option value="{{ route('shop') }}">All Categories</option>
          <option value="{{ route('shop', ['category' => 'Men']) }}" {{ request('category') == 'Men' ? 'selected' : '' }}>Men</option>
          <option value="{{ route('shop', ['category' => 'Women']) }}" {{ request('category') == 'Women' ? 'selected' : '' }}>Women</option>
          <option value="{{ route('shop', ['category' => 'Kids']) }}" {{ request('category') == 'Kids' ? 'selected' : '' }}>Kids</option>
          <option value="{{ route('shop', ['category' => 'Accessories']) }}" {{ request('category') == 'Accessories' ? 'selected' : '' }}>Accessories</option>
        </select>
      </div>

      {{-- Sort --}}
      <div class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Sort by:</label>
        <select onchange="window.location.href = this.value" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10">
          <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest</option>
          <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
          <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
        </select>
      </div>
    </div>
  </div>
</section>

{{-- PRODUCT GRID --}}
<section class="py-10 sm:py-14 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    {{-- Product count --}}
    <div class="mb-6">
      <p class="text-sm text-gray-600">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</p>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse($products as $product)
        <a href="{{ route('product.show', $product->id_produk) }}"
           class="group bg-white rounded-2xl overflow-hidden hover:shadow-lg transition border border-gray-100">
          <div class="aspect-[3/4] bg-gray-100">
            @php
              $img = $product->gambar ? asset('storage/produk/'.$product->gambar) : asset('images/placeholder-product.jpg');
            @endphp
            <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition duration-300" alt="{{ $product->nama_produk }}">
          </div>
          <div class="p-4">
            <h3 class="font-semibold line-clamp-2 text-gray-900">{{ $product->nama_produk }}</h3>
            @if($product->kategori)
              <p class="text-sm text-gray-500 mt-1">{{ $product->kategori }}</p>
            @endif
            <p class="mt-2 font-bold text-gray-900">
              Rp {{ number_format($product->detailUkuran->min('harga') ?? 0, 0, ',', '.') }}
            </p>
          </div>
        </a>
      @empty
        <div class="col-span-full text-center py-16">
          <div class="inline-block p-6 bg-gray-50 rounded-2xl">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Products Found</h3>
            <p class="text-gray-600">Try adjusting your filters or check back later</p>
          </div>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="mt-12">
      <div class="flex justify-center items-center gap-2">
        {{-- Previous --}}
        @if($products->onFirstPage())
          <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Previous</span>
        @else
          <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg">Previous</a>
        @endif

        {{-- Page Numbers --}}
        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
          @if($page == $products->currentPage())
            <span class="px-4 py-2 bg-gray-900 text-white rounded-lg font-medium">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg">{{ $page }}</a>
          @endif
        @endforeach

        {{-- Next --}}
        @if($products->hasMorePages())
          <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg">Next</a>
        @else
          <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Next</span>
        @endif
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
