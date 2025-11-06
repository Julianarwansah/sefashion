<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SeFashion')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), 
                              url('https://images.unsplash.com/photo-1558769132-cb1aea1f1f57?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-white">
      {{-- NAVBAR PIL + CENTERED di area teks --}}
  <nav class="absolute top-8 left-1/2 -translate-x-1/2 z-20">
    <ul class="flex items-center gap-6 px-6 py-3 rounded-full bg-white/80 backdrop-blur-md shadow-md text-sm font-medium">
      <li><a href="{{ route('home') }}" class="hover:text-gray-900 text-gray-700">Home</a></li>
      <li><a href="{{ route('shop') }}" class="hover:text-gray-900 text-gray-700">Shop</a></li>
      <li><a href="#" class="hover:text-gray-900 text-gray-700">About</a></li>
      <li><a href="#" class="hover:text-gray-900 text-gray-700">Contact</a></li>
      <li class="ml-2">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-300 hover:bg-gray-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Account
        </a>
      </li>
      <li>
        <a href="{{ route('shop') }}" class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-900 text-white hover:bg-black">
          Cart
        </a>
      </li>
    </ul>
  </nav>

    <!-- Hero Section (Optional) -->
    @if(isset($showHero) && $showHero)
    <div class="hero-bg relative h-64">
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
            <div class="mb-4">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 4L14 14L24 24L34 14L24 4Z" fill="currentColor" opacity="0.6"/>
                    <path d="M24 24L14 34L24 44L34 34L24 24Z" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold mb-2">@yield('hero-title', 'SeFashion')</h1>
            <div class="text-sm">
                <a href="{{ url('/') }}" class="hover:underline">Home</a>
                <span class="mx-2">/</span>
                <span>@yield('hero-breadcrumb')</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer (Optional) -->
    <footer class="bg-gray-100 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} SeFashion. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>