@extends('admin.layouts.app')

@section('content')

    <div class="space-y-8">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Posts</h1>

            <a href="{{ route('admin.posts.trashed') }}"
                class="px-4 py-2 bg-rose-500 text-white rounded-lg text-sm hover:bg-rose-600 transition">
                View Trashed Posts
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">

            <form method="GET" action="{{ route('admin.posts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Search -->
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search content..." class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800
                              dark:text-slate-200 border border-slate-300 dark:border-slate-600 focus:ring-2
                              focus:ring-sky-500 outline-none">

                <!-- Per Page -->
                <select name="per_page" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800
                               dark:text-slate-200 border border-slate-300 dark:border-slate-600 focus:ring-2
                               focus:ring-sky-500 outline-none">
                    <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="20" {{ ($filters['per_page'] ?? 10) == 20 ? 'selected' : '' }}>20 per page</option>
                    <option value="50" {{ ($filters['per_page'] ?? 10) == 50 ? 'selected' : '' }}>50 per page</option>
                </select>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-sky-600 text-white text-sm rounded-lg hover:bg-sky-700 transition">
                        Apply Filters
                    </button>
                </div>

            </form>

        </div>

        <!-- Posts Table -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">

            <table class="min-w-full text-left divide-y divide-slate-200 dark:divide-slate-700">
                <thead>
                    <tr class="text-sm text-slate-600 dark:text-slate-300 uppercase">
                        <th class="py-3">Media</th>
                        <th class="py-3">Content</th>
                        <th class="py-3">User</th>
                        <th class="py-3">Created</th>
                        <th class="py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-100/40 dark:hover:bg-slate-700/40 transition">

                            <!-- Thumbnail -->
                            <td class="py-3">
                                <div class="w-14 h-14 flex items-center justify-center bg-slate-200 
                                                dark:bg-slate-700 rounded-lg overflow-hidden shadow-sm">
                                    @if($post->media_path)

                                        @php
                                            // Detect file extension
                                            $extension = strtolower(pathinfo($post->media_path, PATHINFO_EXTENSION));
                                            $isVideo = in_array($extension, ['mp4', 'mov', 'avi', 'mkv', 'webm']);
                                        @endphp

                                        @if($isVideo)
                                            <video src="{{ asset($post->media_path) }}" class="w-full h-full object-cover" controls
                                                preload="metadata"></video>
                                        @else
                                            <img src="{{ asset($post->media_path) }}" class="w-full h-full object-cover"
                                                alt="post media">
                                        @endif

                                    @else
                                        {{-- Fallback icon --}}
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 16l4-4a3 3 0 014 0l4 4m0-6l-2-2a2 2 0 00-3 0l-1 1" />
                                            <circle cx="8" cy="8" r="3" />
                                        </svg>
                                    @endif

                                </div>
                            </td>

                            <!-- Content Snippet -->
                            <td class="py-3 max-w-xs text-sm text-slate-800 dark:text-slate-200">
                                @php
                                    $content = strip_tags($post->caption);
                                    $preview = $content ? Str::limit($content, 80) : 'No content';
                                @endphp
                                {{ $preview }}
                            </td>

                            <!-- User -->
                            <td class="py-3">
                                <a href="{{ route('admin.users.show', $post->user_id) }}"
                                    class="text-sm font-semibold text-sky-600 dark:text-sky-400 hover:underline">
                                    {{ $post->user->name ?? 'Unknown' }}
                                </a>
                            </td>

                            <!-- Created -->
                            <td class="py-3 text-sm text-slate-600 dark:text-slate-400">
                                {{ $post->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-right">

                                <!-- View -->
                                <a href="{{ route('admin.posts.show', $post->id) }}" class="px-3 py-1.5 text-sm rounded-lg bg-emerald-100 text-emerald-700
                                              dark:bg-emerald-600/20 dark:text-emerald-300 hover:bg-emerald-200
                                              dark:hover:bg-emerald-600/30 transition">
                                    View
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Move post to trash?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-sm rounded-lg bg-rose-100 text-rose-700
                                                       dark:bg-rose-600/20 dark:text-rose-300 hover:bg-rose-200
                                                       dark:hover:bg-rose-600/30 transition ml-1">
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-500 dark:text-slate-400">
                                No posts found.
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