@extends('admin.layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-900 shadow-lg rounded-xl p-6">

        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            Two-Factor Authentication
        </h2>

        @if (!$user->two_factor_secret)

            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Add an extra layer of security to your admin account.
            </p>

            <form method="POST" action="{{ route('admin.2fa.store') }}">
                @csrf
                <button class="w-full bg-sky-600 hover:bg-sky-700 text-white
                               font-semibold py-3 rounded-lg transition">
                    Enable 2FA
                </button>
            </form>

        @else

            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Scan the QR code below using <strong>Google Authenticator</strong>.
            </p>

            <div class="flex justify-center bg-gray-100 dark:bg-gray-800 p-4 rounded-lg mb-6">
                <div class="border">
                    {!! $user->twoFactorQrCodeSvg() !!}
                </div>
            </div>

            {{-- CONFIRM FORM --}}
            <form method="POST" action="{{ route('admin.2fa.confirm') }}" class="space-y-4">
                @csrf

                <input type="text" name="code" inputmode="numeric" placeholder="Enter 6-digit code" class="w-full px-4 py-3 border rounded-lg
                               border-gray-300 dark:border-gray-700
                               bg-white dark:bg-gray-900
                               text-gray-900 dark:text-gray-100
                               focus:ring-2 focus:ring-sky-500 focus:outline-none" required>

                <button class="w-full bg-green-600 hover:bg-green-700
                               text-white font-semibold py-3 rounded-lg transition">
                    Confirm & Activate
                </button>
            </form>

            {{-- RECOVERY CODES --}}
            <div class="mt-8">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Recovery Codes
                </h3>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    @foreach (auth('admin')->user()->recoveryCodes() as $code)
                        <div class="bg-gray-100 dark:bg-gray-800 px-3 py-2 rounded font-mono text-center">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>

                <p class="text-xs text-gray-500 mt-3">
                    Save these codes securely. They can be used if you lose access to your authenticator app.
                </p>
            </div>

            {{-- DISABLE 2FA --}}
            <div class="mt-10 border-t pt-6">
                <form method="POST" action="{{ route('admin.2fa.disable') }}"
                    onsubmit="return confirm('Are you sure you want to disable Two-Factor Authentication?');">
                    @csrf
                    @method('DELETE')

                    <button class="w-full bg-red-600 hover:bg-red-700
                                   text-white font-semibold py-3 rounded-lg transition">
                        Disable Two-Factor Authentication
                    </button>
                </form>
            </div>

        @endif
    </div>
@endsection