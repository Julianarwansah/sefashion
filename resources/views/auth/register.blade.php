@extends('layouts_public.auth')

@section('title', 'Register • SeFashion')

@section('content')
{{-- REGISTER SECTION --}}
<section class="min-h-screen flex items-center justify-center py-16 px-4 bg-gradient-to-br from-gray-50 to-gray-100">
  <div class="w-full max-w-md">
    {{-- Logo/Brand --}}
    <div class="text-center mb-8 fade-in-up">
      <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Create Account</h1>
      <p class="text-gray-600">Join SeFashion today and start shopping</p>
    </div>

    {{-- Register Card --}}
    <div class="bg-white rounded-2xl shadow-xl p-8 fade-in-up delay-100">
      {{-- Error Messages --}}
      @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg animate-shake">
          <div class="flex items-start">
            <svg class="w-5 h-5 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
              @foreach ($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
              @endforeach
            </div>
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        {{-- Nama Field --}}
        <div class="mb-5">
          <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
            Full Name
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <input
              type="text"
              id="nama"
              name="nama"
              value="{{ old('nama') }}"
              required
              autofocus
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('nama') border-red-500 @enderror"
              placeholder="John Doe"
            >
          </div>
          @error('nama')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Email Field --}}
        <div class="mb-5">
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
            Email Address
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
              </svg>
            </div>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              required
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('email') border-red-500 @enderror"
              placeholder="your@email.com"
            >
          </div>
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Phone Number Field --}}
        <div class="mb-5">
          <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
            Phone Number
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            <input
              type="tel"
              id="no_hp"
              name="no_hp"
              value="{{ old('no_hp') }}"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('no_hp') border-red-500 @enderror"
              placeholder="081234567890"
            >
          </div>
          @error('no_hp')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Address Field --}}
        <div class="mb-5">
          <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
            Address
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <textarea
              id="alamat"
              name="alamat"
              rows="3"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('alamat') border-red-500 @enderror"
              placeholder="Your complete address"
            >{{ old('alamat') }}</textarea>
          </div>
          @error('alamat')
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <input
              type="password"
              id="password"
              name="password"
              required
              class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition @error('password') border-red-500 @enderror"
              placeholder="••••••••"
            >
            <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
              <svg id="eyeIconPassword" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
        </div>

        {{-- Password Confirmation Field --}}
        <div class="mb-5">
          <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
            Confirm Password
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <input
              type="password"
              id="password_confirmation"
              name="password_confirmation"
              required
              class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
              placeholder="••••••••"
            >
            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
              <svg id="eyeIconPasswordConfirmation" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        {{-- Terms & Conditions --}}
        <div class="mb-6">
          <label class="flex items-start cursor-pointer">
            <input
              type="checkbox"
              name="terms"
              id="terms"
              required
              class="mt-1 w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-2 focus:ring-gray-900"
            >
            <span class="ml-2 text-sm text-gray-700">
              I agree to the
              <a href="#" class="text-gray-900 font-medium hover:underline">Terms and Conditions</a>
              and
              <a href="#" class="text-gray-900 font-medium hover:underline">Privacy Policy</a>
            </span>
          </label>
        </div>

        {{-- Submit Button --}}
        <button
          type="submit"
          class="w-full py-3 bg-gray-900 text-white rounded-lg hover:bg-black transition-all font-semibold transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center"
          id="registerButton"
        >
          <span id="buttonText">Create Account</span>
          <svg id="buttonLoader" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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

      {{-- Login Link --}}
      <div class="text-center">
        <p class="text-gray-600">
          Already have an account?
          <a href="{{ route('login') }}" class="text-gray-900 font-semibold hover:underline">
            Login here
          </a>
        </p>
      </div>
    </div>

    {{-- Back to Home --}}
    <div class="text-center mt-6 fade-in-up delay-200">
      <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 text-sm flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Home
      </a>
    </div>
  </div>
</section>
<style>
.fade-in-up {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.6s ease-out forwards;
}

.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-10px); }
  75% { transform: translateX(10px); }
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}
</style>

<script>
function togglePassword(fieldId) {
  const passwordInput = document.getElementById(fieldId);
  const eyeIcon = document.getElementById('eyeIcon' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1).replace('_', ''));

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
  } else {
    passwordInput.type = 'password';
    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
  }
}

document.getElementById('registerForm').addEventListener('submit', function() {
  const button = document.getElementById('registerButton');
  const buttonText = document.getElementById('buttonText');
  const buttonLoader = document.getElementById('buttonLoader');

  button.disabled = true;
  button.classList.add('opacity-75', 'cursor-not-allowed');
  buttonText.textContent = 'Creating account...';
  buttonLoader.classList.remove('hidden');
});
</script>
@endsection