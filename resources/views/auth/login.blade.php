@extends('layouts_public.auth')

@section('title', 'Login • SeFashion')

@section('content')
  {{-- LOGIN SECTION --}}
  <section class="min-h-screen flex items-center justify-center py-16 px-4 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="w-full max-w-md">
      {{-- Logo/Brand --}}
      <div class="text-center mb-8 fade-in-up">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Welcome Back</h1>
        <p class="text-gray-600">Login to your SeFashion account</p>
      </div>

      {{-- Login Card --}}
      <div class="bg-white rounded-2xl shadow-xl p-8 fade-in-up delay-100">
        {{-- Error Messages --}}
        @if ($errors->any())
          <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg animate-shake">
            <div class="flex items-start">
              <svg class="w-5 h-5 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                  clip-rule="evenodd" />
              </svg>
              <div>
                @foreach ($errors->all() as $error)
                  <p class="text-sm">{{ $error }}</p>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
          <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg animate-slide-down">
            <div class="flex items-start">
              <svg class="w-5 h-5 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                  clip-rule="evenodd" />
              </svg>
              <p class="text-sm">{{ session('success') }}</p>
            </div>
          </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" id="loginForm">
          @csrf

          {{-- Email Field --}}
          <div class="mb-5">
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
              Email Address
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
              </div>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('email') border-red-500 @enderror"
                placeholder="your@email.com">
            </div>
            @error('email')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password Field --}}
          <div class="mb-5">
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
              Password
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input type="password" id="password" name="password" required
                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('password') border-red-500 @enderror"
                placeholder="••••••••">
              <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <svg id="eyeIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            @error('password')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Remember & Forgot --}}
          <div class="flex items-center justify-between mb-6">
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" name="remember" id="remember"
                class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-2 focus:ring-gray-900">
              <span class="ml-2 text-sm text-gray-700">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-gray-900 hover:underline font-medium">
              Forgot Password?
            </a>
          </div>

          {{-- Submit Button --}}
          <button type="submit"
            class="w-full py-3 bg-gray-900 text-white rounded-lg hover:bg-black transition-all font-semibold transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center"
            id="loginButton">
            <span id="buttonText">Login</span>
            <svg id="buttonLoader" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
          </button>
        </form>

        {{-- Divider --}}
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or</span>
          </div>
        </div>

        {{-- Google Login Button --}}
        <a href="{{ route('auth.google') }}"
          class="w-full py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3 mb-6">
          <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path fill="#4285F4"
              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
            <path fill="#34A853"
              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
            <path fill="#FBBC05"
              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
            <path fill="#EA4335"
              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
          </svg>
          <span>Login with Google</span>
        </a>

        {{-- Register Link --}}
        <div class="text-center">
          <p class="text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-gray-900 font-semibold hover:underline">
              Register here
            </a>
          </p>
        </div>
      </div>

      {{-- Back to Home --}}
      <div class="text-center mt-6 fade-in-up delay-200">
        <a href="{{ route('home') }}"
          class="text-gray-600 hover:text-gray-900 text-sm flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Home
        </a>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <style>
    .fade-in-up {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.6s ease-out forwards;
    }

    .delay-100 {
      animation-delay: 0.1s;
    }

    .delay-200 {
      animation-delay: 0.2s;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-10px);
      }

      75% {
        transform: translateX(10px);
      }
    }

    .animate-shake {
      animation: shake 0.5s ease-in-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-slide-down {
      animation: slideDown 0.3s ease-out;
    }
  </style>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
      } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
      }
    }

    document.getElementById('loginForm').addEventListener('submit', function () {
      const button = document.getElementById('loginButton');
      const buttonText = document.getElementById('buttonText');
      const buttonLoader = document.getElementById('buttonLoader');

      button.disabled = true;
      button.classList.add('opacity-75', 'cursor-not-allowed');
      buttonText.textContent = 'Logging in...';
      buttonLoader.classList.remove('hidden');
    });
  </script>
@endpush