@extends('layouts_public.app')

@section('title', 'My Account - SeFashion')

@section('hero-title', 'My Account')
@section('hero-breadcrumb', 'My Account')

@php
    $showHero = true;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 py-16">
    <div class="grid md:grid-cols-2 gap-12">
        <!-- Login Form -->
        <div>
            <h2 class="text-3xl font-bold mb-8">Log In</h2>
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Username or email address
                    </label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-400"
                        >
                        <span class="ml-2 text-sm text-gray-700">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Lost Your Password?
                    </a>
                </div>

                <button 
                    type="submit"
                    class="w-full md:w-auto px-12 py-3 bg-white border-2 border-gray-900 text-gray-900 rounded-md hover:bg-gray-900 hover:text-white transition-colors font-medium"
                >
                    Log In
                </button>
            </form>
        </div>

        <!-- Register Form -->
        <div>
            <h2 class="text-3xl font-bold mb-8">Register</h2>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="register-name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input 
                        type="text" 
                        id="register-name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="register-email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email address
                    </label>
                    <input 
                        type="email" 
                        id="register-email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="register-password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="register-password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="register-password-confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password
                    </label>
                    <input 
                        type="password" 
                        id="register-password-confirmation" 
                        name="password_confirmation" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                    >
                </div>

                <div class="mb-6 text-sm text-gray-600 leading-relaxed">
                    Your personal data will be used to support your experience throughout this website, 
                    to manage access to your account, and for other purposes described in our 
                    <a href="{{ url('/privacy-policy') }}" class="text-gray-900 font-medium hover:underline">
                        privacy policy
                    </a>.
                </div>

                <button 
                    type="submit"
                    class="w-full md:w-auto px-12 py-3 bg-white border-2 border-gray-900 text-gray-900 rounded-md hover:bg-gray-900 hover:text-white transition-colors font-medium"
                >
                    Register
                </button>
            </form>
        </div>
    </div>
</div>
@endsection