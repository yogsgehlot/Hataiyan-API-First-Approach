@extends('admin.layouts.app')

@section('content')

    <div class="space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Reports</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">

            <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search reason or user..." class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800
                              dark:text-slate-200 border border-slate-300 dark:border-slate-600 focus:ring-2
                              focus:ring-sky-500 outline-none">

                <select name="per_page" class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-800
                               dark:text-slate-200 border border-slate-300 dark:border-slate-600 ">
                    <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="20" {{ ($filters['per_page'] ?? 10) == 20 ? 'selected' : '' }}>20 per page</option>
                    <option value="50" {{ ($filters['per_page'] ?? 10) == 50 ? 'selected' : '' }}>50 per page</option>
                </select>

                <div>
                    <button class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg text-sm hover:bg-sky-700 transition">
                        Apply Filters
                    </button>
                </div>

            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-xl shadow border border-slate-200 dark:border-slate-700">

            <table class="min-w-full text-left divide-y divide-slate-200 dark:divide-slate-700">
                <thead>
                    <tr class="text-sm text-slate-600 dark:text-slate-300 uppercase">
                        <th class="py-3">User</th>
                        <th class="py-3">Reported Post</th>
                        <th class="py-3">Reason</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-700/40 transition">

                            <!-- Reporter (user who submitted report) -->
                            <td class="py-3">
                                <div class="flex items-center gap-3">

                                    <img src="{{ $report->reporter?->avatar_path ? asset($report->reporter->avatar_path) : asset('images/default-avatar.jpg') }}"
                                        class="h-10 w-10 rounded-full object-cover shadow" />

                                    <div class="text-sm text-slate-800 dark:text-slate-200 leading-tight">
                                        <div class="font-semibold">
                                            {{ $report->reporter->name ?? 'Unknown User' }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            Reported a post by:
                                            <span class="font-medium text-slate-700 dark:text-slate-300">
                                                {{ $report->reportedUser->name ?? 'Unknown' }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <!-- Reported Post -->
                            <td class="py-3 text-sm text-sky-600 dark:text-sky-400">

                                @if($report->post)
                                    <a href="{{ route('admin.posts.show', $report->post_id) }}"
                                        class="hover:underline flex items-center gap-2">

                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M5 13l4 4L19 7"></path>
                                        </svg>

                                        View Post
                                    </a>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400">Post Deleted</span>
                                @endif

                            </td>

                            <!-- Reason -->
                            <td class="py-3 text-sm text-slate-700 dark:text-slate-300 max-w-sm">
                                {{ Str::limit($report->reason, 70) }}

                                @if($report->details)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ Str::limit($report->details, 80) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3">
                                @if($report->status === 'resolved')
                                            <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700 
                                    dark:bg-emerald-600/20 dark:text-emerald-300">
                                                Resolved
                                            </span>
                                @else
                                            <span class="px-3 py-1 rounded-full text-xs bg-rose-100 text-rose-700 
                                    dark:bg-rose-600/20 dark:text-rose-300">
                                                Pending
                                            </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 text-right">

                                @if($report->status !== 'resolved')
                                    <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST"
                                        onsubmit="return confirm('Mark this report as resolved?')">
                                        @csrf
                                        <button
                                            class="px-3 py-1.5 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                            Resolve
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                                @endif

                            </td>

                        </tr>
                    @empty

                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500 dark:text-slate-400">
                                No reports found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>


            </table>

            <div class="mt-6">
                {{ $reports->links('pagination::tailwind') }}
            </div>

        </div>

    </div>

@endsection