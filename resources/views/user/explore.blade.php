@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-6xl mx-auto py-6">

            {{-- Search box --}}
            <div class="relative w-full max-w-lg mx-auto mb-6">
                <input type="text" id="searchUser" placeholder="Search users..." class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 dark:border-gray-700 
                                   bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-500">

                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M16 10a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>

                <div id="searchResults"
                    class="hidden absolute top-14 w-full bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 max-h-64 overflow-y-auto z-50">
                </div>
            </div>

            {{-- Explore Grid --}}
            <div id="exploreGrid" class="max-w-6xl mx-auto px-1">
                <div class="grid grid-cols-3 gap-0.5 p-1" id="postGrid">
                    {{-- Server-render initial posts --}}
                    @php $startIndex = 0; @endphp
                    @foreach ($posts as $index => $post)
                        @if (empty($post['media_path'])) @continue @endif

                        @php $isLarge = ($index % 7) === 0; @endphp

                        <a href="{{ route('post.show', $post['id']) }}" class="relative group overflow-hidden rounded 
                                          bg-gray-200 dark:bg-gray-700 block 
                                          {{ $isLarge ? 'col-span-2 row-span-2 aspect-square' : 'aspect-square' }}">
                            @if (($post['media_type'] ?? 'image') === 'video')
                                <video src="{{ $post['media_path'] }}" class="w-full h-full object-cover pointer-events-none" muted
                                    loop autoplay playsinline></video>
                            @else
                                <img src="{{ $post['media_path'] }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 pointer-events-none"
                                    alt="post">
                            @endif

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100
                                                transition duration-300 flex items-center justify-center gap-6 text-white">

                                <div class="flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                                                         2 6.14 3.99 4 6.5 4c1.74 0 3.41 1.01 
                                                         4.22 2.61C11.09 5.01 12.76 4 
                                                         14.5 4 17.01 4 19 6.14 19 
                                                         8.5c0 3.78-3.4 6.86-8.55 
                                                         11.54L12 21.35z" />
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $post['likes_count'] ?? 0 }}</span>
                                </div>

                                <div class="flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 6h-2v9H7v2a1 1 0 001 1h11l4 
                                                         4V7a1 1 0 00-1-1zM17 2H3a1 
                                                         1 0 00-1 1v16l4-4h11a1 1 
                                                         0 001-1V3a1 1 0 00-1-1z" />
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $post['comments_count'] ?? 0 }}</span>
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            </div> 
            {{-- Infinite scroll trigger element --}}
            <div id="loadMoreTrigger" class="h-12"></div>

            {{-- Loading indicator (hidden by default) --}}
            <div id="loadingIndicator" class="text-center py-4 hidden">
                <span class="text-gray-600 dark:text-gray-300">Loading...</span>
            </div>

        </div>
    </div>
 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
       
                $('#searchUser').on('input', function () {
                    let query = $(this).val().trim();
                    let resultsDiv = $('#searchResults');

                    if (query.length < 2) {
                        resultsDiv.addClass('hidden').html('');
                        return;
                    }

                    $.ajax({
                        url: '{{ route('search') }}',
                        method: 'GET',
                        data: { q: query },
                        success: function (data) {
                            if (!Array.isArray(data) || data.length === 0) {
                                resultsDiv.html(`
                                            <div class='p-3 text-gray-500 dark:text-gray-300 text-sm'>
                                                No users found
                                            </div>
                                        `).removeClass('hidden');
                                return;
                            }

                            let html = '';
                            data.forEach(user => {
                                html += `
                                            <a href="/profile/${user.username}" 
                                                class="flex items-center gap-3 p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition rounded-lg">

                                                <img src="${user.avatar_path ?? '/images/default-avatar.jpg'}" class="w-10 h-10 rounded-full object-cover">

                                                <div>
                                                    <p class="font-semibold text-gray-800 dark:text-gray-200">${user.username}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">${user.name ?? ''}</p>
                                                </div>
                                            </a>
                                        `;
                            });

                            resultsDiv.html(html).removeClass('hidden');
                        },
                        error: function () {
                            resultsDiv.html(`
                                        <div class='p-3 text-red-500 text-sm'>
                                            Error loading results
                                        </div>
                                    `).removeClass('hidden');
                        }
                    });
                });

                // Hide dropdown when clicking outside
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#searchUser, #searchResults').length) {
                        $('#searchResults').addClass('hidden');
                    }
                });

 
                let nextPage = {{ $nextPage ?? 1 }};
                let isLoading = false;


                // start from number of rendered posts
                let globalIndex = {{ count($posts) }};

                const $postGrid = $('#postGrid');
                const $loadMoreTrigger = document.getElementById('loadMoreTrigger');
                const $loadingIndicator = $('#loadingIndicator');

                // escape HTML
                function escapeHtml(unsafe) {
                    if (unsafe === null || unsafe === undefined) return '';
                    return String(unsafe)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                // Build HTML like server-side
                function renderPostCard(post, index) {
                    const isLarge = (index % 7) === 0;
                    const mediaPath = escapeHtml(post.media_path || '');
                    const mediaType = escapeHtml(post.media_type || 'image');

                    let inner = mediaType === 'video'
                        ? `<video src="${mediaPath}" class="w-full h-full object-cover pointer-events-none" muted loop autoplay playsinline></video>`
                        : `<img src="${mediaPath}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 pointer-events-none" alt="post">`;

                    return `
                        <a href="/post/${post.id}" 
                        class="relative group overflow-hidden rounded bg-gray-200 dark:bg-gray-700 block 
                        ${isLarge ? 'col-span-2 row-span-2 aspect-square' : 'aspect-square'}">

                            ${inner}

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 
                                        transition duration-300 flex items-center justify-center gap-6 text-white">

                                <div class="flex items-center gap-1">
                                    ❤️ <span>${post.likes_count ?? 0}</span>
                                </div>

                                <div class="flex items-center gap-1">
                                    💬 <span>${post.comments_count ?? 0}</span>
                                </div>

                            </div>
                        </a>
                    `;
                }

                // AJAX load more
                function loadMorePosts() {
                    if (isLoading || !nextPage) return;
                    isLoading = true;
                    $loadingIndicator.removeClass('hidden');
                
                    $.ajax({
                        url: "/explore/load-more",
                        type: "GET",
                        data: { page: nextPage },
                        success: function (res) {

                            if (!res.data || res.data.length === 0) {
                                nextPage = null;
                                return;
                            }

                            let html = "";
                            res.data.forEach(post => {
                                if (!post.media_path) return;
                                html += renderPostCard(post, globalIndex);
                                globalIndex++;
                            });

                            $postGrid.append(html);

                            // Set next page
                            nextPage = res.next_page_url ? nextPage + 1 : null;
                        },
                        error: function (xhr) {
                            console.error("AJAX Load Error:", xhr.responseText);
                        },
                        complete: function () {
                            $loadingIndicator.addClass('hidden');
                            isLoading = false;
                        }
                    });
                }

                // IntersectionObserver to trigger load
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        loadMorePosts();
                    }
                }, {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.1
                });

                observer.observe($loadMoreTrigger);

            });
        </script>
 

@endsection