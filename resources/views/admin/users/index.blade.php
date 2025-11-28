@extends('admin.layouts.app')

@section('content')

    <div class="space-y-8">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Users</h1>

            <a href="{{ route('admin.users.trashed') }}"
                class="px-4 py-2 bg-rose-500 text-white rounded-lg text-sm hover:bg-rose-600 transition">
                View Trashed Users
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Search -->
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search name or email"
                    class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800
                                                                                                                                                                                                   dark:text-slate-200 border border-slate-300 dark:border-slate-600 focus:ring-2
                                                                                                                                                                                                   focus:ring-sky-500 outline-none" />

                <!-- Status -->
                <select name="status"
                    class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800
                                                                                                                                                                                                   dark:text-slate-200 border border-slate-300 dark:border-slate-600 focus:ring-2
                                                                                                                                                                                                   focus:ring-sky-500 outline-none">
                    <option value="">Any Status</option>
                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="banned" {{ ($filters['status'] ?? '') === 'banned' ? 'selected' : '' }}>Banned</option>
                </select>

                <!-- Button -->
                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700 transition">
                        Apply Filters
                    </button>
                </div>

            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">

            <table class="min-w-full text-left divide-y divide-slate-200 dark:divide-slate-700">
                <thead>
                    <tr class="text-sm text-slate-600 dark:text-slate-300 uppercase">
                        <th class="py-3">User</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Joined</th>
                        <th class="py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                    @foreach($users as $user)
                        <tr class="hover:bg-slate-100/40 dark:hover:bg-slate-700/40 transition">

                            <!-- User + Avatar -->
                            <td class="py-3 flex items-center gap-3">
                                <img src="{{ $user->avatar_path ? asset($user->avatar_path) : asset('images/default-avatar.jpg') }}"
                                    class="h-10 w-10 rounded-full object-cover shadow-sm" />
                                <div>
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="font-semibold text-slate-800 dark:text-slate-200 hover:underline">
                                        {{ $user->name }}
                                    </a>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        ID: {{ $user->id }}
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3 text-sm text-slate-700 dark:text-slate-300">
                                {{ $user->email }}
                            </td>

                            <!-- Joined -->
                            <td class="py-3 text-sm text-slate-600 dark:text-slate-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-right">

                                <!-- View -->
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="px-3 py-1.5 text-sm rounded-lg bg-sky-100 text-sky-800 
                                                                                                                                                                                                                                                                                                                                                                                              dark:bg-sky-600/20 dark:text-sky-300 hover:bg-sky-200 
                                                                                                                                                                                                                                                                                                                                                                                              dark:hover:bg-sky-600/30 transition">
                                    View
                                </a>

                                <!-- Soft Delete -->
                                <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline"
                                    onsubmit="return confirm('Move user to trash?')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-600/20 dark:text-rose-300 hover:bg-rose-200 dark:hover:bg-rose-600/30 transition ml-1">
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $users->links('pagination::tailwind') }}
            </div>

        </div>

    </div>

@endsection