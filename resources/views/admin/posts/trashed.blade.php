@extends('admin.layouts.app')

@section('content')

<div class="space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Trashed Posts</h1>

        <a href="{{ route('admin.posts.index') }}"
           class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700 transition">
            ← Back to Active Posts
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">

        <table class="min-w-full text-left divide-y divide-slate-200 dark:divide-slate-700">
            <thead>
                <tr class="text-sm text-slate-600 dark:text-slate-300 uppercase">
                    <th class="py-3">Media</th>
                    <th class="py-3">Content</th>
                    <th class="py-3">User</th>
                    <th class="py-3">Deleted At</th>
                    <th class="py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                @forelse($posts as $post)
                    <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-700/40 transition">

                        <!-- Media -->
                        <td class="py-3 flex items-center gap-3">
                            <div class="w-14 h-14 bg-slate-200 dark:bg-slate-700 rounded-lg overflow-hidden shadow flex items-center justify-center">
                                @if($post->media_path)
                                    <img src="{{ asset($post->media_path) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 16l4-4a3 3 0 014 0l4 4m0-6l-2-2a2 2 0 00-3 0l-1 1" />
                                        <circle cx="8" cy="8" r="3" />
                                    </svg>
                                @endif
                            </div>
                        </td>

                        <!-- Content -->
                        <td class="py-3 text-sm text-slate-700 dark:text-slate-300 max-w-xs">
                            @php
                                $content = strip_tags($post->caption);
                                $preview = $content ? Str::limit($content, 80) : 'No content';
                            @endphp
                            {{ $preview }}
                        </td>

                        <!-- User -->
                        <td class="py-3 text-sm">
                            <div class="text-sky-600 dark:text-sky-400 font-semibold">
                                {{ $post->user->name ?? 'Unknown' }}
                            </div>
                        </td>

                        <!-- Deleted At -->
                        <td class="py-3 text-sm text-rose-600 dark:text-rose-400">
                            {{ $post->deleted_at->format('d M Y, h:i A') }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3 text-right flex items-center gap-3 justify-end">

                            <!-- Restore -->
                            <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1.5 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                    Restore
                                </button>
                            </form>

                            <!-- Permanent Delete -->
                            <form action="{{ route('admin.posts.delete', $post->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Permanently delete this post?')">
                                @csrf
                                <button class="px-3 py-1.5 text-sm rounded-lg bg-rose-600 text-white hover:bg-rose-700 transition">
                                    Delete Forever
                                </button>
                            </form>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-500 dark:text-slate-400">
                            No trashed posts found.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $posts->links('pagination::tailwind') }}
        </div>

    </div>

</div>

@endsection
