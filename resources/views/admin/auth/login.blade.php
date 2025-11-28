@extends('admin.layouts.app')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center">

    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8 animate-slide-in">

        <h2 class="text-2xl font-semibold text-center mb-6">Admin Sign In</h2>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 
                px-4 py-3 rounded-md">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-800
                    dark:text-slate-100 rounded-md outline-none focus:ring-2 focus:ring-sky-500"
                    required />
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-800
                    dark:text-slate-100 rounded-md outline-none focus:ring-2 focus:ring-sky-500"
                    required />
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-2.5 text-center font-semibold rounded-md
                bg-sky-600 hover:bg-sky-700 text-white transition">
                Sign In
            </button>

        </form>

    </div>

</div>

@endsection
