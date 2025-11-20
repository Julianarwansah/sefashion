<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SeFashion')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>


    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                              url('https://images.unsplash.com/photo-1558769132-cb1aea1f1f57?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
        }

        /* Navbar Sticky Animation */
        .navbar-sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            transition: all 0.3s ease-in-out;
        }

        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Add padding to body to prevent content jump */
        body.has-sticky-nav {
            padding-top: 80px;
        }

        /* Mobile menu animation */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .mobile-menu.active {
            max-height: 500px;
        }

        @media (max-width: 768px) {
            body.has-sticky-nav {
                padding-top: 70px;
            }
        }
    </style>

    <!-- Page Specific Styles -->
    @stack('styles')
</head>
<body class="bg-white has-sticky-nav">
    {{-- NAVBAR STICKY - Lebih Besar & Mengikuti Scroll --}}
    <nav class="navbar-sticky bg-white/95 backdrop-blur-md shadow-lg" id="mainNavbar">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 md:py-4">
            <div class="flex items-center justify-between">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-gray-800 to-gray-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L6 7L12 12L18 7L12 2Z" fill="white" opacity="0.8"/>
                                <path d="M12 12L6 17L12 22L18 17L12 12Z" fill="white"/>
                            </svg>
                        </div>
                        <span class="text-xl md:text-2xl font-bold text-gray-900">SeFashion</span>
                    </a>
                </div>

                {{-- Desktop Navigation Links --}}
                <ul class="hidden lg:flex items-center gap-8 text-base font-medium">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-gray-900 text-gray-700 transition-colors {{ Request::routeIs('home') ? 'text-gray-900 font-semibold' : '' }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop') }}" class="hover:text-gray-900 text-gray-700 transition-colors {{ Request::routeIs('shop') ? 'text-gray-900 font-semibold' : '' }}">
                            Shop
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="hover:text-gray-900 text-gray-700 transition-colors {{ Request::routeIs('about') ? 'text-gray-900 font-semibold' : '' }}">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="hover:text-gray-900 text-gray-700 transition-colors {{ Request::routeIs('contact') ? 'text-gray-900 font-semibold' : '' }}">
                            Contact
                        </a>
                    </li>
                </ul>

                {{-- Right Side: Account & Cart --}}
                <div class="flex items-center gap-2 md:gap-4">
                    {{-- Account Dropdown - Hidden on mobile --}}
                    <div class="hidden md:block">
                        @auth('customer')
                            <div class="relative group">
                                <button class="inline-flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 rounded-lg border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-medium text-sm md:text-base hidden sm:inline">{{ Auth::guard('customer')->user()->nama }}</span>
                                    <svg class="w-3 h-3 md:w-4 md:h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="p-3">
                                        <div class="px-3 py-2 border-b border-gray-100">
                                            <p class="text-sm font-semibold text-gray-900">{{ Auth::guard('customer')->user()->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ Auth::guard('customer')->user()->email }}</p>
                                        </div>
                                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mt-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            My Account
                                        </a>
                                        <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            My Orders
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 pt-2 mt-2">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg w-full text-left">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 rounded-lg border-2 border-gray-300 hover:border-gray-400 hover:bg-gray-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="font-medium text-sm md:text-base hidden sm:inline">Account</span>
                            </a>
                        @endauth
                    </div>

                    {{-- Cart Button --}}
                    <div>
                        @auth('customer')
                            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1.5 md:gap-2 px-3 md:px-5 py-2 md:py-2.5 rounded-lg bg-gray-900 text-white hover:bg-black transition-all shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-medium text-sm md:text-base">Cart</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 md:gap-2 px-3 md:px-5 py-2 md:py-2.5 rounded-lg bg-gray-900 text-white hover:bg-black transition-all shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-medium text-sm md:text-base">Cart</span>
                            </a>
                        @endauth
                    </div>

                    {{-- Mobile Menu Toggle --}}
                    <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-all" id="mobileMenuBtn">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div class="mobile-menu lg:hidden" id="mobileMenu">
                <div class="pt-4 pb-3 border-t border-gray-200 mt-3">
                    <div class="space-y-1">
                        <a href="{{ route('home') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors {{ Request::routeIs('home') ? 'bg-gray-50 text-gray-900' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('shop') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors {{ Request::routeIs('shop') ? 'bg-gray-50 text-gray-900' : '' }}">
                            Shop
                        </a>
                        <a href="{{ route('about') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors {{ Request::routeIs('about') ? 'bg-gray-50 text-gray-900' : '' }}">
                            About
                        </a>
                        <a href="{{ route('contact') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors {{ Request::routeIs('contact') ? 'bg-gray-50 text-gray-900' : '' }}">
                            Contact
                        </a>

                        {{-- Mobile Account Menu --}}
                        @auth('customer')
                            <div class="pt-3 border-t border-gray-200">
                                <div class="px-4 py-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::guard('customer')->user()->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::guard('customer')->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors">
                                    My Account
                                </a>
                                <a href="{{ route('my-orders') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors">
                                    My Orders
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-base font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2.5 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors">
                                Login / Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
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

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-12 md:mt-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-8 md:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-6 md:mb-8">
                {{-- Company Info --}}
                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-white to-gray-300 rounded-lg flex items-center justify-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L6 7L12 12L18 7L12 2Z" fill="#1F2937" opacity="0.8"/>
                                <path d="M12 12L6 17L12 22L18 17L12 12Z" fill="#1F2937"/>
                            </svg>
                        </div>
                        <span class="text-xl md:text-2xl font-bold">SeFashion</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Your ultimate destination for trendy and affordable fashion. Discover the latest styles that express your unique personality.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
                                <path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Home</a></li>
                        <li><a href="{{ route('shop') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Shop</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>

                {{-- Customer Service --}}
                <div>
                    <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Track Order</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Returns & Exchanges</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Size Guide</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h3 class="text-lg font-semibold mb-4">Stay Updated</h3>
                    <p class="text-gray-400 text-sm mb-4">Subscribe to get special offers and latest trends.</p>
                    <form class="space-y-3">
                        <input
                            type="email"
                            placeholder="Enter your email"
                            class="w-full px-4 py-2.5 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-white/50 text-sm"
                        >
                        <button type="submit" class="w-full px-4 py-2.5 bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition-all font-medium text-sm">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-white/10 pt-6 mt-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} SeFashion. All rights reserved.
                    </p>
                    <div class="flex gap-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 w-12 h-12 bg-gray-900 text-white rounded-full shadow-lg hover:bg-black transition-all opacity-0 invisible z-40 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <!-- Cart Notification Modal -->
    @include('partials.cart-notification-modal')

    <!-- Scripts -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = mobileMenuBtn.contains(event.target) || mobileMenu.contains(event.target);
            if (!isClickInside && mobileMenu.classList.contains('active')) {
                mobileMenu.classList.remove('active');
            }
        });

        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'invisible');
            } else {
                scrollToTopBtn.classList.add('opacity-0', 'invisible');
            }
        });

        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    @stack('scripts')
</body>
</html>