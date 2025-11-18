@extends('layouts_public.app')

@section('title', 'Shop • SeFashion')

@section('content')
{{-- HERO BANNER WITH SHOP TEXT --}}
<section class="relative h-[40vh] sm:h-[50vh] min-h-[300px] sm:min-h-[400px] w-full overflow-hidden">
  {{-- background image with parallax --}}
  <div
    id="shopHeroImage"
    class="absolute inset-0 bg-center bg-cover transition-transform duration-300"
    style="background-image:url('{{ asset('images/hero-rack.jpg') }}');">
  </div>

  {{-- dark overlay --}}
  <div class="absolute inset-0 bg-black/40"></div>

  {{-- Animated floating elements - hidden on mobile --}}
  <div class="hidden md:block absolute top-20 left-20 w-24 h-24 bg-white/5 rounded-full blur-2xl animate-float"></div>
  <div class="hidden md:block absolute bottom-20 right-32 w-32 h-32 bg-white/5 rounded-full blur-3xl animate-float-delayed"></div>

  {{-- Centered Shop Text --}}
  <div class="relative z-10 h-full flex items-center justify-center px-4">
    <div class="text-center">
      <h1 class="text-white text-4xl sm:text-5xl md:text-6xl font-extrabold animate-fade-in-up">
        Shop
      </h1>
      <p class="mt-3 sm:mt-4 text-white/80 text-base sm:text-lg animate-fade-in-up animation-delay-200">Discover Your Perfect Style</p>
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
<section class="py-4 sm:py-6 border-b bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
      {{-- Filter by Category --}}
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
        <label class="text-sm font-medium text-gray-700">Category:</label>
        <select onchange="window.location.href = this.value" class="border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10">
          <option value="{{ route('shop') }}">All Categories</option>
          <option value="{{ route('shop', ['category' => 'Men']) }}" {{ request('category') == 'Men' ? 'selected' : '' }}>Men</option>
          <option value="{{ route('shop', ['category' => 'Women']) }}" {{ request('category') == 'Women' ? 'selected' : '' }}>Women</option>
          <option value="{{ route('shop', ['category' => 'Kids']) }}" {{ request('category') == 'Kids' ? 'selected' : '' }}>Kids</option>
          <option value="{{ route('shop', ['category' => 'Accessories']) }}" {{ request('category') == 'Accessories' ? 'selected' : '' }}>Accessories</option>
        </select>
      </div>

      {{-- Sort --}}
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
        <label class="text-sm font-medium text-gray-700">Sort by:</label>
        <select onchange="window.location.href = this.value" class="border border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900/10">
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
        @auth('customer')
          {{-- Jika sudah login, link ke product detail --}}
          <a href="{{ route('product.show', $product->id_produk) }}"
             class="group bg-white rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-gray-300 scroll-reveal opacity-0 translate-y-8">
            <div class="aspect-[3/4] bg-gray-100 overflow-hidden">
              <img src="{{ $product->gambar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $product->nama_produk }}">
            </div>
            <div class="p-4">
              <h3 class="font-semibold line-clamp-2 text-gray-900 group-hover:text-black transition">{{ $product->nama_produk }}</h3>
              @if($product->kategori)
                <p class="text-sm text-gray-500 mt-1">{{ $product->kategori }}</p>
              @endif
              <p class="mt-2 font-bold text-gray-900">
                Rp {{ number_format($product->detailUkuran->min('harga') ?? 0, 0, ',', '.') }}
              </p>
            </div>
          </a>
        @else
          {{-- Jika belum login, link ke login page dengan pesan --}}
          <a href="{{ route('login') }}" 
             onclick="showLoginAlert()"
             class="group bg-white rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-gray-300 scroll-reveal opacity-0 translate-y-8 cursor-pointer">
            <div class="aspect-[3/4] bg-gray-100 overflow-hidden">
              <img src="{{ $product->gambar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $product->nama_produk }}">
            </div>
            <div class="p-4">
              <h3 class="font-semibold line-clamp-2 text-gray-900 group-hover:text-black transition">{{ $product->nama_produk }}</h3>
              @if($product->kategori)
                <p class="text-sm text-gray-500 mt-1">{{ $product->kategori }}</p>
              @endif
              <p class="mt-2 font-bold text-gray-900">
                Rp {{ number_format($product->detailUkuran->min('harga') ?? 0, 0, ',', '.') }}
              </p>
              <div class="mt-2 text-xs text-blue-600 font-medium">
                Login to view details
              </div>
            </div>
          </a>
        @endauth
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
<script>
  // Function to show login alert
function showLoginAlert() {
    if (!document.querySelector('meta[name="customer-auth"]')?.content) {
        alert('Please login first to view product details');
    }
}

// Check auth status and add meta tag
document.addEventListener('DOMContentLoaded', function() {
    const isAuthenticated = @json(auth('customer')->check());
    if (!document.querySelector('meta[name="customer-auth"]')) {
        const meta = document.createElement('meta');
        meta.name = 'customer-auth';
        meta.content = isAuthenticated ? 'true' : 'false';
        document.head.appendChild(meta);
    }
});
</script>

<style>
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

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

@keyframes floatDelayed {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-25px);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
}

.animation-delay-200 {
  animation-delay: 0.2s;
  opacity: 0;
}

.animate-float {
  animation: float 5s ease-in-out infinite;
}

.animate-float-delayed {
  animation: floatDelayed 7s ease-in-out infinite;
  animation-delay: 1.5s;
}

.scroll-reveal {
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}

.scroll-reveal.revealed {
  opacity: 1 !important;
  transform: translateY(0) !important;
}
</style>
<script>
// Parallax effect for shop hero
document.addEventListener('scroll', function() {
  const heroImage = document.getElementById('shopHeroImage');
  if (heroImage) {
    const scrolled = window.pageYOffset;
    const rate = scrolled * 0.3;
    heroImage.style.transform = `translateY(${rate}px)`;
  }
});

// Scroll reveal for products
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
  entries.forEach((entry, index) => {
    if (entry.isIntersecting) {
      // Staggered animation
      setTimeout(() => {
        entry.target.classList.add('revealed');
      }, index * 50);
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);

document.addEventListener('DOMContentLoaded', function() {
  const scrollRevealElements = document.querySelectorAll('.scroll-reveal');
  scrollRevealElements.forEach(el => observer.observe(el));
});
</script>
@endsection
