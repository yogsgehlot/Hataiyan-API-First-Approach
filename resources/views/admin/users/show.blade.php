@extends('admin.layouts.app')

@section('content')

    <div class="space-y-8 max-w-4xl mx-auto">

        <!-- Back Button -->
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-sky-600 hover:underline dark:text-sky-400">
                ← Back to Users
            </a>
        </div>

        <!-- Profile Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-8 shadow-lg border border-slate-200 dark:border-slate-700">

            <!-- Top section -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

                <!-- Avatar -->
                <img src="{{ $user->avatar_path ? asset($user->avatar_path) : asset('images/default-avatar.jpg') }}"
                    class="w-28 h-28 rounded-full border border-slate-300 dark:border-slate-700 object-cover shadow" />

                <!-- User Info -->
                <div class="flex-1 space-y-3">

                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                            {{ $user->name }}
                        </h1>

                        @if($user->trashed())
                            <span
                                class="px-3 py-1 text-xs rounded-full bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">
                                Deleted
                            </span>
                        @else
                            <span
                                class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                                Active
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        <strong>Email:</strong> {{ $user->email }}
                    </p>

                    @if($user->bio)
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                            {{ $user->bio }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Divider -->
            <div class="my-6 border-b border-slate-200 dark:border-slate-700"></div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div>
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 text-sm uppercase mb-1">User ID</h3>
                    <p class="text-slate-900 dark:text-slate-200">{{ $user->id }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 text-sm uppercase mb-1">Email Verified</h3>
                    <p class="text-slate-900 dark:text-slate-200">
                        {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not Verified' }}
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 text-sm uppercase mb-1">Joined</h3>
                    <p class="text-slate-900 dark:text-slate-200">
                        {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-slate-700 dark:text-slate-300 text-sm uppercase mb-1">Last Updated</h3>
                    <p class="text-slate-900 dark:text-slate-200">
                        {{ $user->updated_at->format('d M Y') }}
                    </p>
                </div>

                @if($user->trashed())
                    <div>
                        <h3 class="font-semibold text-rose-600 text-sm uppercase mb-1 dark:text-rose-400">Deleted At</h3>
                        <p class="text-rose-600 dark:text-rose-400">
                            {{ $user->deleted_at->format('d M Y h:i A') }}
                        </p>
                    </div>
                @endif

            </div>

            <!-- Divider -->
            <div class="my-6 border-b border-slate-200 dark:border-slate-700"></div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">

                <!-- Restore Button -->
                @if($user->trashed())
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                            Restore User
                        </button>
                    </form>
                @endif

                <!-- Delete -->
                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure?')">
                    @csrf

                    @if($user->trashed())
                        <button type="submit"
                            class="px-4 py-2 bg-rose-600 text-white text-sm rounded-lg hover:bg-rose-700 transition">
                            Delete Permanently
                        </button>
                    @else
                        <button type="submit"
                            class="px-4 py-2 bg-rose-500 text-white text-sm rounded-lg hover:bg-rose-600 transition">
                            Move to Trash
                        </button>
                    @endif
                </form>

            </div>

        </div>

    </div>

@endsection