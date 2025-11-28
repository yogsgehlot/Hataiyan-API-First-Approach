@extends('layouts.app')
@section('title', 'Register')

@section('content')
  <div class="flex items-center justify-center   transition-colors duration-300">
    <div
      class="flex flex-col md:flex-row bg-white dark:bg-gray-900 shadow-2xl rounded-2xl overflow-hidden max-w-5xl w-full mx-4 ">

      <!-- Left Section -->
      <div
        class="hidden md:flex md:w-1/2 flex-col items-center justify-center text-white  p-10">
        <img src="/images/Hataiyan_logo_pink.png" alt="Hataiyan" class="w-48 mb-6 animate-fade-in">
        <h1 class="text-3xl font-bold mb-2">Join <span class="text-pink-500">Hataiyan</span></h1>
        <p class="text-sm opacity-80">Create your account and start sharing your story.</p>
      </div>

      <!-- Right Section -->
      <div class="flex flex-col justify-center items-center md:w-1/2 w-full p-8">
        <div class="w-full max-w-sm">

          <!-- Header -->
          <div class="text-center mb-6">
            <img src="/images/Hataiyan_logo_pink.png" alt="Hataiyan" class="w-10 inline-block mr-2">
            <span class="text-2xl font-bold text-gray-800 dark:text-white align-middle">Hataiyan</span>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create your account to get started</p>
          </div>

          <!-- Validation Errors -->
          @if ($errors->any())
            <div class="bg-red-500/10 border border-red-400 text-red-500 px-4 py-2 rounded-md text-sm mb-4">
              <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Form -->
          <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
              <div class="relative">
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter full name"
                  class="w-full px-4 py-2 pl-10 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-pink-500" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
            </div>

            <!-- Username -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
              <div class="relative">
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Enter username"
                  class="w-full px-4 py-2 pl-10 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-pink-500" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <div class="relative">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter email address"
                  class="w-full px-4 py-2 pl-10 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-pink-500" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z" />
                </svg>
              </div>
            </div>

            <!-- Password -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
              <div class="relative">
                <input type="password" name="password" id="password" placeholder="Enter password"
                  class="w-full px-4 py-2 pl-10 pr-10 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-pink-500" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 11c0-1.657 1.343-3 3-3h3a3 3 0 013 3v3a3 3 0 01-3 3h-3a3 3 0 01-3-3v-3z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 11V8a4 4 0 014-4h8a4 4 0 014 4v3" />
                </svg>
                <button type="button" id="toggle-password"
                  class="absolute right-3 top-2.5 text-gray-400 hover:text-pink-500 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Confirm Password -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
              <div class="relative">
                <input type="password" name="password_confirmation" placeholder="Re-enter password"
                  class="w-full px-4 py-2 pl-10 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-pink-500" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
              class="w-full bg-gradient-to-r from-pink-500 to-fuchsia-500 text-white font-semibold py-2 rounded-full flex items-center justify-center gap-2 hover:opacity-90 transition border">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
              </svg>
              Create Account
            </button>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-600 dark:text-gray-400 mt-3">
              Already have an account?
              <a href="{{ route('login') }}" class="text-pink-500 hover:underline font-medium">Login</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Password Toggle -->
  <script>
    // document.querySelector('#toggle-password').forEach(btn => {
    //   btn.addEventListener('click', () => {
    //     const input = document.getElementById(btn.dataset.toggle);
    //     input.type = input.type === 'password' ? 'text' : 'password';
    //     btn.classList.toggle('text-pink-500');
    //   });
    // });

    $(document).ready(function() {
      $('#toggle-password').on('click', function() {
        const input = $('#password');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass('text-pink-500');
      });
    });
  </script>
@endsection