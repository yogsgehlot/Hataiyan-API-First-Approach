@extends('admin.layouts.app')

@section('content')

    <div class="space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Admins</h1>

            <a href="{{ route('admin.admins.create') }}"
                class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700 transition">
                Create New Admin
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">

            <table class="min-w-full divide-y text-left divide-slate-200 dark:divide-slate-700">
                <thead>
                    <tr class="uppercase text-sm text-slate-600 dark:text-slate-300">
                        <th class="py-3">Name</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Created</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                    @foreach($admins as $admin)
                        <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-700/40 transition">

                            <td class="py-3 font-semibold">
                                {{ $admin->name }}
                            </td>

                            <td class="py-3 text-slate-600 dark:text-slate-300">
                                {{ $admin->email }}
                            </td>

                            <td class="py-3">
                                <span class="px-3 py-1 rounded-full text-xs bg-sky-100 text-sky-700 
                        dark:bg-sky-600/20 dark:text-sky-300">
                                    {{ ucfirst($admin->role) }}
                                </span>
                            </td>

                            <td class="py-3 text-sm text-slate-500 dark:text-slate-400">
                                {{ $admin->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-right space-x-2">

                                <!-- Edit -->
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="px-3 py-1.5 text-sm rounded-lg bg-amber-100 text-amber-700
                              dark:bg-amber-600/20 dark:text-amber-300 hover:bg-amber-200
                              dark:hover:bg-amber-600/30 transition">
                                    Edit
                                </a>

                                <!-- Delete -->
                                @if(session('admin')['id'] != $admin->id)
                                    <form action="{{ route('admin.admins.delete', $admin->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Delete this admin account?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="px-3 py-1.5 text-sm rounded-lg bg-rose-100 text-rose-700
                                           dark:bg-rose-600/20 dark:text-rose-300 hover:bg-rose-200
                                           dark:hover:bg-rose-600/30 transition">
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500">Self</span>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

            <div class="mt-6">
                {{ $admins->links('pagination::tailwind') }}
            </div>

        </div>

    </div>

@endsection