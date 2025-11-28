@extends('admin.layouts.app')

@section('content')

    <div class="space-y-10">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Welcome back,
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ session('admin')['name'] ?? 'Admin' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

            <!-- Users -->
            <div class="bg-gradient-to-br from-sky-500 to-sky-600 text-white rounded-xl p-6 shadow-lg 
                    hover:shadow-xl transform transition-all hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium opacity-80">Total Users</p>
                        <h3 class="text-4xl font-bold mt-1">{{ $totalUsers }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg">
                        <svg class="h-7 w-7" fill="none" stroke="white" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 7a4 4 0 110-8 4 4 0 010 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Posts -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-xl p-6 shadow-lg
                    hover:shadow-xl transform transition-all hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium opacity-80">Total Posts</p>
                        <h3 class="text-4xl font-bold mt-1">{{ $totalPosts }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg">
                        <svg class="h-7 w-7" fill="none" stroke="white" stroke-width="2">
                            <path d="M19 21H5a2 2 0 01-2-2V7l7-5h6l7 5v12a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Reports -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-xl p-6 shadow-lg 
                    hover:shadow-xl transform transition-all hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium opacity-80">Reports</p>
                        <h3 class="text-4xl font-bold mt-1">{{ $totalReports }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-lg">
                        <svg class="h-7 w-7" fill="none" stroke="white" stroke-width="2">
                            <path d="M12 9v2m0 4h.01M3 12a9 9 0 118 8.94" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold flex items-center gap-2">
                    <svg class="h-6 w-6 text-sky-500" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Recent Users
                </h2>
                <a href="{{ route('admin.users.index') }}"
                    class="text-xs font-medium text-sky-600 hover:text-sky-500 dark:text-sky-400 dark:hover:text-sky-300">
                    View All →
                </a>
            </div>

            @if($recentUsers->count())
                <div class="divide-y divide-slate-200 dark:divide-slate-700">

                    @foreach($recentUsers as $u)
                        <div class="py-4 flex items-center justify-between hover:bg-slate-100/70 
                                            dark:hover:bg-slate-700/40 transition-all rounded-lg px-3">

                            <!-- Left: Avatar + Details -->
                            <div class="flex items-center gap-3">

                                <!-- Avatar -->
                                <img src="{{ $u->avatar_path ? asset($u->avatar_path) : asset('images/default-avatar.jpg') }}"
                                    class="w-10 h-10 rounded-full object-cover shadow-sm" />

                                <!-- Info -->
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $u->name }}
                                    </span>

                                    <span class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $u->email }}
                                    </span>

                                    <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                        Joined {{ $u->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Right: View Button -->
                            <a href="{{ route('admin.users.show', $u->id) }}" class="text-sm px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 
                                               dark:bg-sky-600/20 dark:text-sky-300 hover:bg-sky-100 
                                               dark:hover:bg-sky-600/30 transition">
                                View
                            </a>
                        </div>
                    @endforeach

                </div>

            @else
                <p class="text-slate-500 text-sm">No users found.</p>
            @endif
        </div>


        <!-- Recent Posts -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold flex items-center gap-2">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 01-2-2V7l7-5h6l7 5v12z" />
                    </svg>
                    Recent Posts
                </h2>

                <a href="{{ route('admin.posts.index') }}"
                    class="text-xs font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">
                    View All →
                </a>
            </div>

            @if($recentPosts->count())

                <div class="divide-y divide-slate-200 dark:divide-slate-700">

                    @foreach($recentPosts as $p)
                        <div class="py-4 flex items-start gap-4 hover:bg-slate-100/70 dark:hover:bg-slate-700/40
                                transition-all rounded-lg px-3">

                            <!-- Thumbnail -->
                            <div
                                class="w-14 h-14 rounded-lg bg-slate-200 dark:bg-slate-700 overflow-hidden flex items-center justify-center shadow-sm">
                                @if($p->media_path)
                                    <img src="{{ asset($p->media_path) }}" class="w-full h-full object-cover" />
                                @else
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 16l4-4a3 3 0 014 0l4 4m0-6l-2-2a2 2 0 00-3 0l-1 1" />
                                        <circle cx="8" cy="8" r="3" />
                                    </svg>
                                @endif
                            </div>

                            <!-- Post Details -->
                            <div class="flex flex-col flex-1">

                                @php
                                    $content = trim(strip_tags($p->caption ?? ''));
                                    $contentPreview = $content
                                        ? Str::limit($content, 80)
                                        : ($p->media_path ? 'Media Post' : "Post #{$p->id}");
                                @endphp

                                <span class="font-semibold text-slate-800 dark:text-slate-200 leading-tight">
                                    {{ $contentPreview }}
                                </span>

                                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    by {{ $p->user->name ?? 'Unknown User' }}
                                    • {{ $p->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <!-- View Button -->
                            <a href="#" class="text-sm px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 
                                   dark:bg-emerald-600/20 dark:text-emerald-300 hover:bg-emerald-100 
                                   dark:hover:bg-emerald-600/30 transition">
                                View
                            </a>
                        </div>
                    @endforeach

                </div>

            @else
                <p class="text-slate-500 text-sm">No posts found.</p>
            @endif
        </div>


        <!-- Recent Reports -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-lg border border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01M3 12a9 9 0 118 8.94" />
                </svg>
                Recent Reports
            </h2>

            @if($recentReports->count())
                <div class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($recentReports as $r)
                        <div class="py-4 hover:bg-slate-100/60 dark:hover:bg-slate-700/40 transition rounded-lg px-2">
                            <div class="font-semibold">Report #{{ $r->id }}</div>
                            <div class="text-xs text-slate-500">
                                {{ $r->reason ?? 'No reason provided' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-500 text-sm">No reports found.</p>
            @endif
        </div>

    </div>

@endsection