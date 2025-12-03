@extends('admin.layouts.app')

@section('content')

    <div class="space-y-8 max-w-4xl mx-auto">

        <!-- Back -->
        <div>
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-sky-600 hover:underline dark:text-sky-400">
                ← Back to Posts
            </a>
        </div>

        <!-- Post Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-8 shadow-lg border border-slate-200 dark:border-slate-700">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
                    Post Details
                </h1>

                @if($post->trashed())
                    <span class="px-3 py-1 text-xs rounded-full bg-rose-100 text-rose-700 
                            dark:bg-rose-500/20 dark:text-rose-300">
                        Deleted
                    </span>
                @else
                    <span class="px-3 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700 
                            dark:bg-emerald-500/20 dark:text-emerald-300">
                        Active
                    </span>
                @endif
            </div>

            <!-- Post Content -->
            <div class="space-y-6">

                <!-- User Info -->
                <div class="flex items-center gap-3">
                    <img src="{{ $post->user->avatar_path ? asset($post->user->avatar_path) : asset('images/default-avatar.jpg') }}"
                        class="w-12 h-12 rounded-full object-cover border border-slate-300 dark:border-slate-600">

                    <div>
                        <div class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ $post->user->name ?? 'Unknown User' }}
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            Posted on {{ $post->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Media -->
                @if($post->media_path)

                    @php
                        $ext = strtolower(pathinfo($post->media_path, PATHINFO_EXTENSION));
                        $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm']);
                    @endphp

                    <div class="rounded-xl overflow-hidden shadow border border-slate-200 dark:border-slate-700">

                        @if($isVideo)
                            <video src="{{ asset($post->media_path) }}" class="w-full object-cover" controls
                                preload="metadata"></video>
                        @else
                            <img src="{{ asset($post->media_path) }}" class="w-full object-cover" alt="post media" />
                        @endif

                    </div>

                @endif


                <!-- Content -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">Content</h3>

                    <p class="text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">
                        {{ $post->caption ?: 'No text content' }}
                    </p>
                </div>

            </div>

            <!-- Divider -->
            <div class="my-6 border-b border-slate-200 dark:border-slate-700"></div>

            <!-- Meta -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <h4 class="text-sm text-slate-500 dark:text-slate-400 uppercase font-semibold mb-1">
                        Post ID
                    </h4>
                    <p class="text-slate-800 dark:text-slate-200">{{ $post->id }}</p>
                </div>

                @if($post->trashed())
                    <div>
                        <h4 class="text-sm text-rose-500 uppercase font-semibold mb-1 dark:text-rose-300">
                            Deleted At
                        </h4>
                        <p class="text-rose-600 dark:text-rose-300">
                            {{ $post->deleted_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                @endif

            </div>

            <!-- Divider -->
            <div class="my-6 border-b border-slate-200 dark:border-slate-700"></div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">

                @if($post->trashed())
                    <!-- Restore -->
                    <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                            Restore Post
                        </button>
                    </form>

                    <!-- Permanent Delete -->
                    <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST"
                        onsubmit="return confirm('Permanently delete this post?')">
                        @csrf
                        <button class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
                            Delete Permanently
                        </button>
                    </form>
                @else
                    <!-- Move to trash -->
                    <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST"
                        onsubmit="return confirm('Move this post to trash?')">
                        @csrf
                        <button class="px-4 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition">
                            Move to Trash
                        </button>
                    </form>
                @endif

            </div>

        </div>

    </div>

@endsection