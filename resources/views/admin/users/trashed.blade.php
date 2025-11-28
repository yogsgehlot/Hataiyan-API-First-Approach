@extends('admin.layouts.app')

@section('content')

<div class="space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Trashed Users</h1>

        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700 transition">
            ← Back to Active Users
        </a>
    </div>

    <!-- Trashed Users Table -->
    <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">

        <table class="min-w-full text-left divide-y divide-slate-200 dark:divide-slate-700">
            <thead>
                <tr class="text-sm text-slate-600 dark:text-slate-300 uppercase">
                    <th class="py-3">User</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Deleted At</th>
                    <th class="py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                @forelse($users as $user)
                    <tr class="hover:bg-slate-100/40 dark:hover:bg-slate-700/40 transition">

                        <td class="py-3 flex items-center gap-3">
                            <img src="{{ $user->avatar_path ? asset($user->avatar_path) : asset('images/default-avatar.jpg') }}"
                                 class="h-10 w-10 rounded-full object-cover shadow-sm">

                            <div>
                                <span class="font-semibold text-slate-700 dark:text-slate-200">
                                    {{ $user->name }}
                                </span>
                                <div class="text-xs text-slate-500 dark:text-slate-400">ID: {{ $user->id }}</div>
                            </div>
                        </td>

                        <td class="py-3 text-sm text-slate-700 dark:text-slate-300">
                            {{ $user->email }}
                        </td>

                        <td class="py-3 text-sm text-rose-600 dark:text-rose-400">
                            {{ $user->deleted_at->format('d M Y, h:i A') }}
                        </td>

                        <td class="py-3 text-right flex items-center gap-3 justify-end">

                            <!-- Restore -->
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1.5 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                    Restore
                                </button>
                            </form>

                            <!-- Permanent Delete -->
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                  onsubmit="return confirm('Permanently delete this user?')">
                                @csrf
                                <button class="px-3 py-1.5 text-sm rounded-lg bg-rose-600 text-white hover:bg-rose-700 transition">
                                    Delete Forever
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-slate-500 dark:text-slate-400">
                            No trashed users.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

        <div class="mt-5">
            {{ $users->links('pagination::tailwind') }}
        </div>

    </div>

</div>

@endsection
