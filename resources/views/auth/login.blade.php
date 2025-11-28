@extends('layouts.app')
@section('title', 'Login')

@section('content')
    <div
        class="  flex items-center justify-center  transition-colors duration-300">
        <div
            class="flex flex-col md:flex-row bg-white dark:bg-gray-900 shadow-2xl rounded-2xl overflow-hidden max-w-5xl w-full mx-4">

            <!-- Left Side -->
            <div
                class="hidden md:flex md:w-1/2 flex-col items-center justify-center   text-white p-10">
                <img src="/images/Hataiyan_logo_pink.png" alt="Hataiyan" class="w-48 mb-6 animate-fade-in">
                <h1 class="text-3xl font-bold mb-2">Welcome to <span class="text-white">Hataiyan</span></h1>
                <p class="text-sm opacity-80">Connect. Create. Celebrate your moments.</p>
            </div>

            <!-- Right Side (Login Form) -->
            <div class="flex flex-col justify-center items-center md:w-1/2 w-full p-8">
                <div class="w-full max-w-sm">
                    <div class="text-center mb-6">
                        <img src="/images/Hataiyan_logo_pink.png" alt="Hataiyan" class="w-10 inline-block mr-2">
                        <span class="text-2xl font-bold text-gray-800 dark:text-white align-middle">Hataiyan</span>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Log in to continue sharing your story</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-4">
                        @csrf

                        <!-- Email / Username -->
                        <div>
                            <label for="login-identifier"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email or
                                Username</label>
                            <div class="relative">
                                <input type="text" id="login-identifier" name="identifier" value="{{ old('identifier') }}"
                                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100"
                                    placeholder="Enter email or username" required>
                            </div>
                            @error('identifier')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="login-password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" id="login-password" name="password"
                                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-pink-500 focus:outline-none text-gray-900 dark:text-gray-100"
                                    placeholder="Enter password" required>
                                <button type="button" id="toggle-password"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-400">
                                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Error Message -->
                        <div id="login-error" class="hidden text-red-500 text-sm text-center font-medium"></div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <button type="submit" id="login-submit"
                                class="w-full flex justify-center items-center bg-gradient-to-r from-pink-500 to-fuchsia-500 text-white font-semibold py-2 rounded-full hover:opacity-90 transition disabled:opacity-70 border">
                                <span id="login-text" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                    </svg>
                                    Log in
                                </span>
                                <svg id="login-loading" class="hidden animate-spin h-5 w-5 ml-2 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-6">
                        <a href="/register" class="text-pink-500 dark:text-pink-400 font-medium hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Create an account
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById("toggle-password").addEventListener("click", function () {
            const passwordInput = document.getElementById("login-password");
            const icon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3.98 8.223A10.477 10.477 0 001.458 12C2.732 16.057 6.523 19 11 19c2.196 0 4.218-.704 5.857-1.906M9.88 9.88a3 3 0 104.243 4.243M15 12a3 3 0 01-3 3m9.042-3A10.477 10.477 0 0013 5.64" />`;
            } else {
                passwordInput.type = "password";
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        });
    </script>
@endsection