@extends('layouts_public.app')

@section('title', 'Order Details • SeFashion')

@section('content')
<section class="py-12 bg-gray-50 min-h-screen">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Back Button --}}
    <div class="mb-6">
      <a href="{{ route('my-orders') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My Orders
      </a>
    </div>

    {{-- Order Header --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-extrabold text-gray-900 mb-2">
            Order #{{ str_pad($order->id_pemesanan, 6, '0', STR_PAD_LEFT) }}
          </h1>
          <p class="text-gray-600">
            Placed on {{ \Carbon\Carbon::parse($order->tanggal_pemesanan)->format('d M Y, H:i') }}
          </p>
        </div>
        <div>
          @php
            $statusConfig = [
              'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending Payment'],
              'diproses' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Processing'],
              'dikirim' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Shipped'],
              'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Completed'],
              'batal' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Cancelled'],
            ];
            $status = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($order->status)];
          @endphp
          <span class="inline-block px-6 py-3 rounded-xl text-base font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
            {{ $status['label'] }}
          </span>
        </div>
      </div>

      {{-- Order Timeline --}}
      <div class="border-t border-gray-200 pt-6">
        <h3 class="font-bold text-gray-900 mb-4">Order Timeline</h3>
        <div class="relative">
          <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
          <div class="space-y-6">
            {{-- Order Placed --}}
            <div class="relative flex items-start gap-4">
              <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 relative z-10">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Order Placed</p>
                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->tanggal_pemesanan)->format('d M Y, H:i') }}</p>
              </div>
            </div>

            {{-- Payment Status --}}
            @if($order->pembayaran)
            <div class="relative flex items-start gap-4">
              <div class="w-8 h-8 rounded-full {{ $order->pembayaran->status === 'paid' ? 'bg-green-500' : 'bg-yellow-500' }} flex items-center justify-center flex-shrink-0 relative z-10">
                @if($order->pembayaran->status === 'paid')
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                @else
                <div class="w-3 h-3 rounded-full bg-white"></div>
                @endif
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Payment {{ ucfirst($order->pembayaran->status) }}</p>
                <p class="text-sm text-gray-600">
                  Method: {{ ucfirst($order->pembayaran->metode_pembayaran) }}
                  @if($order->pembayaran->channel)
                    ({{ strtoupper($order->pembayaran->channel) }})
                  @endif
                </p>
              </div>
            </div>
            @endif

            {{-- Processing --}}
            @if(in_array($order->status, ['diproses', 'dikirim', 'selesai']))
            <div class="relative flex items-start gap-4">
              <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 relative z-10">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Order Processing</p>
                <p class="text-sm text-gray-600">Your order is being prepared</p>
              </div>
            </div>
            @endif

            {{-- Shipped --}}
            @if(in_array($order->status, ['dikirim', 'selesai']))
            <div class="relative flex items-start gap-4">
              <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 relative z-10">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Order Shipped</p>
                @if($order->pengiriman)
                <p class="text-sm text-gray-600">
                  Courier: {{ $order->pengiriman->ekspedisi }} ({{ $order->pengiriman->layanan }})
                  @if($order->pengiriman->no_resi)
                    <br>Tracking: {{ $order->pengiriman->no_resi }}
                  @endif
                </p>
                @endif
              </div>
            </div>
            @endif

            {{-- Delivered --}}
            @if($order->status === 'selesai')
            <div class="relative flex items-start gap-4">
              <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 relative z-10">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Order Delivered</p>
                <p class="text-sm text-gray-600">Your order has been completed</p>
              </div>
            </div>
            @endif

            {{-- Cancelled --}}
            @if($order->status === 'batal')
            <div class="relative flex items-start gap-4">
              <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0 relative z-10">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">Order Cancelled</p>
                <p class="text-sm text-gray-600">This order has been cancelled</p>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {{-- Order Items --}}
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-6">Order Items</h2>
          <div class="space-y-4">
            @foreach($order->detailPemesanan as $item)
            <div class="flex items-center gap-4 pb-4 border-b border-gray-200 last:border-0 last:pb-0">
              <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                @php
                  $primaryImage = $item->detailUkuran->produk->gambarProduk->where('is_primary', 1)->first();
                @endphp
                @if($primaryImage)
                  <img src="{{ asset('storage/produk/images/' . $primaryImage->gambar) }}"
                       alt="{{ $item->detailUkuran->produk->nama_produk }}"
                       class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 mb-1">{{ $item->detailUkuran->produk->nama_produk }}</h3>
                <p class="text-sm text-gray-600 mb-2">
                  Size: {{ $item->detailUkuran->ukuran }} •
                  Color: {{ $item->detailUkuran->detailWarna->nama_warna ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-600">Quantity: {{ $item->jumlah }}</p>
                <p class="text-sm text-gray-600">Price: Rp {{ number_format($item->subtotal / $item->jumlah, 0, ',', '.') }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold text-gray-900 text-lg">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Order Summary & Details --}}
      <div class="space-y-6">
        {{-- Customer Details --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Customer Details</h2>
          <div class="space-y-3 text-sm">
            <div>
              <p class="text-gray-600 mb-1">Name</p>
              <p class="font-semibold text-gray-900">{{ $order->customer->nama }}</p>
            </div>
            <div>
              <p class="text-gray-600 mb-1">Email</p>
              <p class="font-semibold text-gray-900">{{ $order->customer->email }}</p>
            </div>
            <div>
              <p class="text-gray-600 mb-1">Phone</p>
              <p class="font-semibold text-gray-900">{{ $order->customer->no_hp }}</p>
            </div>
          </div>
        </div>

        {{-- Shipping Address --}}
        @if($order->pengiriman)
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Shipping Address</h2>
          <div class="space-y-3 text-sm">
            <div>
              <p class="text-gray-600 mb-1">Recipient</p>
              <p class="font-semibold text-gray-900">{{ $order->pengiriman->nama_penerima }}</p>
            </div>
            <div>
              <p class="text-gray-600 mb-1">Phone</p>
              <p class="font-semibold text-gray-900">{{ $order->pengiriman->no_hp_penerima }}</p>
            </div>
            <div>
              <p class="text-gray-600 mb-1">Address</p>
              <p class="font-semibold text-gray-900">{{ $order->pengiriman->alamat_tujuan }}</p>
            </div>
            <div>
              <p class="text-gray-600 mb-1">Courier</p>
              <p class="font-semibold text-gray-900">{{ $order->pengiriman->ekspedisi }} - {{ $order->pengiriman->layanan }}</p>
            </div>
            @if($order->pengiriman->no_resi)
            <div>
              <p class="text-gray-600 mb-1">Tracking Number</p>
              <p class="font-semibold text-gray-900 font-mono">{{ $order->pengiriman->no_resi }}</p>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- Order Summary --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
          <div class="space-y-3">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span class="font-semibold text-gray-900">
                Rp {{ number_format($order->detailPemesanan->sum('subtotal'), 0, ',', '.') }}
              </span>
            </div>
            @if($order->pengiriman)
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Shipping Cost</span>
              <span class="font-semibold text-gray-900">
                Rp {{ number_format($order->pengiriman->biaya_ongkir, 0, ',', '.') }}
              </span>
            </div>
            @endif
            <div class="border-t border-gray-200 pt-3 flex justify-between">
              <span class="font-bold text-gray-900">Total</span>
              <span class="font-bold text-gray-900 text-xl">
                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
              </span>
            </div>
          </div>
        </div>

        {{-- Actions --}}
        @if($order->status === 'pending')
        <form action="{{ route('order.cancel', $order->id_pemesanan) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to cancel this order?')">
          @csrf
          <button type="submit"
                  class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-red-300 text-red-700 rounded-xl hover:bg-red-50 transition font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Cancel This Order
          </button>
        </form>
        @endif
      </div>
    </div>

  </div>
</section>
@endsection
