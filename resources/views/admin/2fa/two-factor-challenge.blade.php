@extends('admin.layouts.app')

@section('content')
    <div class="  flex items-center justify-center bg-gray-100 dark:bg-gray-950 ">

        <div class="w-full max-w-md bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8">

            {{-- Header --}}
            <div class="text-center ">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Two-Factor Authentication
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400  ">
                    Enter the 6-digit code from your authenticator app
                </p>
            </div>

            <form method="POST" action="{{ route('admin.2fa.confirm') }}" class="space-y-6">
                @csrf

                {{-- OTP --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Authentication Code
                    </label>

                    <input type="text" name="code" inputmode="numeric" placeholder="6-digit code" class="w-full text-center tracking-widest text-xl font-semibold
                       px-4 py-3 rounded-lg border">
                </div>

                {{-- OR --}}
                <div class="text-center text-sm text-gray-500">
                    OR
                </div>

                {{-- Recovery Code --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        Recovery Code
                    </label>

                    <input type="text" name="recovery_code" placeholder="Enter recovery code"
                        class="w-full px-4 py-3 rounded-lg border font-mono">
                </div>

                <button class="w-full bg-sky-600 hover:bg-sky-700
                   text-white font-semibold py-3 rounded-lg">
                    Verify & Continue
                </button>
            </form>


            {{-- Logout --}}
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-6">
                @csrf
                <button class="w-full text-sm text-gray-500 hover:text-red-600 transition">
                    Cancel & Logout
                </button>
            </form>

        </div>
    </div>
@endsection