@extends('layouts_public.app')

@section('title', $product->nama_produk . ' • SeFashion')

@section('content')
{{-- BREADCRUMB --}}
<section class="py-6 bg-gray-50 mt-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center gap-2 text-sm text-gray-600">
      <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
      <a href="{{ route('shop') }}" class="hover:text-gray-900">Shop</a>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
      <span class="text-gray-900 font-medium">{{ $product->nama_produk }}</span>
    </div>
  </div>
</section>

{{-- PRODUCT DETAIL --}}
<section class="py-10 sm:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
      {{-- Product Images --}}
      <div class="space-y-4">
        {{-- Main Image --}}
        <div class="aspect-[4/5] bg-gray-100 rounded-2xl overflow-hidden">
          <img id="mainProductImage" src="{{ $product->gambar_url }}" class="w-full h-full object-cover" alt="{{ $product->nama_produk }}">
        </div>

        {{-- Thumbnail Images --}}
        @if($product->gambarProduk->count() > 0)
        <div class="grid grid-cols-4 gap-3">
          @if($product->gambar)
          <button onclick="changeMainImage('{{ $product->gambar_url }}')"
                  class="aspect-square bg-gray-100 rounded-lg overflow-hidden hover:ring-2 hover:ring-gray-900 transition">
            <img src="{{ $product->gambar_url }}" class="w-full h-full object-cover" alt="Thumbnail">
          </button>
          @endif

          @foreach($product->gambarProduk->take(3) as $gambar)
          <button onclick="changeMainImage('{{ asset('storage/produk/images/'.$gambar->gambar) }}')"
                  class="aspect-square bg-gray-100 rounded-lg overflow-hidden hover:ring-2 hover:ring-gray-900 transition">
            <img src="{{ asset('storage/produk/images/'.$gambar->gambar) }}" class="w-full h-full object-cover" alt="Thumbnail">
          </button>
          @endforeach
        </div>
        @endif
      </div>

      {{-- Product Info --}}
      <div class="space-y-6">
        {{-- Category --}}
        @if($product->kategori)
        <div>
          <span class="inline-block px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
            {{ $product->kategori }}
          </span>
        </div>
        @endif

        {{-- Product Name --}}
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
          {{ $product->nama_produk }}
        </h1>

        {{-- Price Range --}}
        <div class="space-y-2">
          @php
            $minPrice = $product->detailUkuran->min('harga');
            $maxPrice = $product->detailUkuran->max('harga');
          @endphp

          @if($minPrice == $maxPrice)
            <p class="text-3xl font-bold text-gray-900">
              Rp {{ number_format($minPrice, 0, ',', '.') }}
            </p>
          @else
            <p class="text-3xl font-bold text-gray-900">
              Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp {{ number_format($maxPrice, 0, ',', '.') }}
            </p>
          @endif
        </div>

        {{-- Description --}}
        @if($product->deskripsi)
        <div class="prose prose-sm max-w-none text-gray-600">
          <p>{{ $product->deskripsi }}</p>
        </div>
        @endif

        {{-- Divider --}}
        <hr class="border-gray-200">

        {{-- Select Color --}}
        @if($product->detailWarna->count() > 0)
        <div class="space-y-3">
          <label class="block text-sm font-semibold text-gray-900">Select Color:</label>
          <div class="flex flex-wrap gap-3">
            @foreach($product->detailWarna as $index => $warna)
            <button type="button"
                    onclick="selectColor({{ $warna->id_warna }}, '{{ $warna->nama_warna }}')"
                    class="color-option px-4 py-2 border-2 border-gray-300 rounded-lg hover:border-gray-900 transition {{ $index == 0 ? 'border-gray-900 bg-gray-900 text-white' : 'bg-white text-gray-900' }}"
                    data-color-id="{{ $warna->id_warna }}">
              <div class="flex items-center gap-2">
                @if($warna->kode_warna)
                <span class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $warna->kode_warna }}"></span>
                @endif
                <span class="text-sm font-medium">{{ $warna->nama_warna }}</span>
              </div>
            </button>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Select Size & Price --}}
        @if($product->detailUkuran->count() > 0)
        <div class="space-y-3">
          <label class="block text-sm font-semibold text-gray-900">Select Size:</label>
          <div id="sizeOptions" class="flex flex-wrap gap-3">
            @foreach($product->detailUkuran as $index => $ukuran)
            <button type="button"
                    onclick="selectSize({{ $ukuran->id_ukuran }}, '{{ $ukuran->ukuran }}', {{ $ukuran->harga }}, {{ $ukuran->stok }})"
                    class="size-option px-4 py-2 border-2 border-gray-300 rounded-lg hover:border-gray-900 transition {{ $index == 0 ? 'border-gray-900 bg-gray-900 text-white' : 'bg-white text-gray-900' }}"
                    data-size-id="{{ $ukuran->id_ukuran }}"
                    data-color-id="{{ $ukuran->id_warna }}"
                    data-price="{{ $ukuran->harga }}"
                    data-stock="{{ $ukuran->stok }}">
              <div class="text-sm font-medium">
                {{ $ukuran->ukuran }}
                @if($ukuran->stok <= 0)
                <span class="text-xs text-red-500">(Sold Out)</span>
                @endif
              </div>
            </button>
            @endforeach
          </div>

          {{-- Selected price display --}}
          <div id="selectedPrice" class="text-lg font-bold text-gray-900">
            Price: Rp <span id="priceValue">{{ number_format($product->detailUkuran->first()->harga, 0, ',', '.') }}</span>
          </div>

          {{-- Stock indicator --}}
          <div id="stockIndicator" class="text-sm">
            <span class="inline-flex items-center gap-1.5">
              <span class="w-2 h-2 bg-green-500 rounded-full"></span>
              <span class="text-gray-600">In Stock: <span id="stockValue" class="font-semibold text-gray-900">{{ $product->detailUkuran->first()->stok }}</span> available</span>
            </span>
          </div>
        </div>
        @endif

        {{-- Quantity & Add to Cart --}}
        <div class="space-y-4">
          <div class="flex items-center gap-4">
            {{-- Quantity Selector --}}
            <div class="flex items-center border border-gray-300 rounded-lg">
              <button type="button" onclick="decreaseQuantity()" class="px-4 py-3 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
              </button>
              <input type="number" id="quantity" value="1" min="1" readonly class="w-16 text-center border-x border-gray-300 py-3 focus:outline-none">
              <button type="button" onclick="increaseQuantity()" class="px-4 py-3 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
              </button>
            </div>

            {{-- Add to Cart Button --}}
            <button type="button"
                    onclick="addToCart()"
                    id="addToCartBtn"
                    class="flex-1 px-8 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
              Add to Cart
            </button>
          </div>
        </div>

        {{-- Product Features --}}
        <div class="space-y-3 pt-6 border-t border-gray-200">
          <div class="flex items-center gap-3 text-sm text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>High Quality Materials</span>
          </div>
          <div class="flex items-center gap-3 text-sm text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Free Shipping Over Rp 500.000</span>
          </div>
          <div class="flex items-center gap-3 text-sm text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Easy Returns Within 14 Days</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- RELATED PRODUCTS --}}
@if($relatedProducts->count() > 0)
<section class="py-10 sm:py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Related Products</h2>
      <p class="mt-2 text-gray-600">You might also like these</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach($relatedProducts as $relatedProduct)
        <a href="{{ route('product.show', $relatedProduct->id_produk) }}"
           class="group bg-white rounded-2xl overflow-hidden hover:shadow-lg transition border border-gray-100">
          <div class="aspect-[3/4] bg-gray-100">
            <img src="{{ $relatedProduct->gambar_url }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition duration-300" alt="{{ $relatedProduct->nama_produk }}">
          </div>
          <div class="p-4">
            <h3 class="font-semibold line-clamp-2 text-gray-900">{{ $relatedProduct->nama_produk }}</h3>
            @if($relatedProduct->kategori)
              <p class="text-sm text-gray-500 mt-1">{{ $relatedProduct->kategori }}</p>
            @endif
            <p class="mt-2 font-bold text-gray-900">
              Rp {{ number_format($relatedProduct->detailUkuran->min('harga') ?? 0, 0, ',', '.') }}
            </p>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection

@push('scripts')
<script>
let selectedColorId = {{ $product->detailWarna->first()->id_warna ?? 'null' }};
let selectedSizeId = {{ $product->detailUkuran->first()->id_ukuran ?? 'null' }};
let currentStock = {{ $product->detailUkuran->first()->stok ?? 0 }};

// Get CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function changeMainImage(imageUrl) {
    document.getElementById('mainProductImage').src = imageUrl;
}

function selectColor(colorId, colorName) {
    selectedColorId = colorId;

    // Update button styles
    document.querySelectorAll('.color-option').forEach(btn => {
        btn.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
        btn.classList.add('border-gray-300', 'bg-white', 'text-gray-900');
    });

    event.target.closest('.color-option').classList.remove('border-gray-300', 'bg-white', 'text-gray-900');
    event.target.closest('.color-option').classList.add('border-gray-900', 'bg-gray-900', 'text-white');

    // Filter size options by color
    filterSizesByColor(colorId);
}

function filterSizesByColor(colorId) {
    const sizeOptions = document.querySelectorAll('.size-option');
    let firstAvailable = null;

    sizeOptions.forEach(option => {
        const optionColorId = parseInt(option.dataset.colorId);

        if (optionColorId === colorId) {
            option.style.display = 'block';
            if (!firstAvailable && parseInt(option.dataset.stock) > 0) {
                firstAvailable = option;
            }
        } else {
            option.style.display = 'none';
        }
    });

    if (firstAvailable) {
        const sizeId = firstAvailable.dataset.sizeId;
        const sizeName = firstAvailable.querySelector('.text-sm').textContent.trim();
        const price = parseInt(firstAvailable.dataset.price);
        const stock = parseInt(firstAvailable.dataset.stock);
        selectSize(sizeId, sizeName, price, stock);
    } else {
        selectedSizeId = null;
        currentStock = 0;
        updateAddToCartButton();
    }
}

function selectSize(sizeId, sizeName, price, stock) {
    selectedSizeId = sizeId;
    currentStock = stock;

    document.querySelectorAll('.size-option').forEach(btn => {
        if (btn.style.display !== 'none') {
            btn.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
            btn.classList.add('border-gray-300', 'bg-white', 'text-gray-900');
        }
    });

    event.target.closest('.size-option').classList.remove('border-gray-300', 'bg-white', 'text-gray-900');
    event.target.closest('.size-option').classList.add('border-gray-900', 'bg-gray-900', 'text-white');

    document.getElementById('priceValue').textContent = new Intl.NumberFormat('id-ID').format(price);

    document.getElementById('stockValue').textContent = stock;

    const stockIndicator = document.getElementById('stockIndicator').querySelector('span.w-2');
    if (stock > 10) {
        stockIndicator.className = 'w-2 h-2 bg-green-500 rounded-full';
    } else if (stock > 0) {
        stockIndicator.className = 'w-2 h-2 bg-yellow-500 rounded-full';
    } else {
        stockIndicator.className = 'w-2 h-2 bg-red-500 rounded-full';
    }

    document.getElementById('quantity').value = 1;

    updateAddToCartButton();
}

function updateAddToCartButton() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (!selectedSizeId || currentStock <= 0) {
        addToCartBtn.disabled = true;
        addToCartBtn.textContent = 'Out of Stock';
    } else {
        addToCartBtn.disabled = false;
        addToCartBtn.textContent = 'Add to Cart';
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);

    if (currentValue < currentStock) {
        quantityInput.value = currentValue + 1;
    } else {
        showToast('Maximum stock available: ' + currentStock, 'warning');
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);

    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}


function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);

    if (!selectedSizeId) {
        showToast('Please select a size', 'warning');
        return;
    }

    if (currentStock <= 0) {
        showToast('This product is out of stock', 'error');
        return;
    }

    if (quantity > currentStock) {
        showToast('Maximum stock available: ' + currentStock, 'error');
        return;
    }

    const addToCartBtn = document.getElementById('addToCartBtn');
    const originalText = addToCartBtn.textContent;
    addToCartBtn.disabled = true;
    addToCartBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Adding...</span>';

    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            id_ukuran: selectedSizeId,
            jumlah: quantity
        })
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || `HTTP error! status: ${response.status}`);
        }
        
        return data;
    })
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            
            updateCartCount(data.cart_count);
            
        } else {
            throw new Error(data.message || 'Failed to add to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        if (error.message.includes('login') || error.message.includes('Session expired')) {
            showToast('Please login to add items to cart', 'error');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else {
            showToast(error.message || 'Failed to add item to cart', 'error');
        }
    })
    .finally(() => {
        // Restore button state
        addToCartBtn.disabled = false;
        addToCartBtn.textContent = originalText;
    });
}


function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-badge, .cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function showToast(message, type = 'info') {
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `custom-toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-semibold transform translate-x-full transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'warning' ? 'bg-yellow-500' : 
        'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    if (selectedColorId) {
        filterSizesByColor(selectedColorId);
    }
    updateAddToCartButton();
});
</script>

<style>
.custom-toast {
    max-width: 300px;
    word-wrap: break-word;
}
</style>
@endpush