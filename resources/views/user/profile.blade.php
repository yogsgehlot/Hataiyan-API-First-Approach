@extends('layouts.app')

@section('title', $user['username'] . ' • Hataiyan')

@section('content')
    <div
        class="max-w-5xl mx-auto bg-white/20 dark:bg-gray-900 rounded-xl transition-colors duration-300 border border-gray-300 dark:border-gray-700">

        <!-- Cover -->
        <div class="relative mb-20">
            <img src="{{ $user['cover'] ?? asset('images/background-empty-picture.jpg') }}" alt="Cover Photo"
                class="w-full h-52 object-cover rounded-xl">

            <!-- Profile Image -->
            <div class="absolute -bottom-16 left-6">
                <img src="{{ $user['avatar_path'] ?? asset('images/default-avatar.jpg') }}" alt="Profile"
                    class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-900 object-cover shadow-lg">
            </div>
        </div>

        <!-- User Info -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="pl-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user['name'] }}</h2>
                <p class="text-gray-500 mb-1">{{ $user['username'] }}</p>
                <p class="text-gray-700 dark:text-gray-300 text-sm mb-3">
                    {!! formatCaption($user['bio'] ?? 'No bio yet.') !!}
                </p>
                <div class="flex  items-center w-full  space-x-8 pb-4">

                    <!-- Followers & Following -->
                    <div class="flex items-center ">
                        @livewire('user.follow-stats', ['user' => $user])
                    </div>

                    <!-- Follow Button -->
                    @if(session()->has('user') && session('user.id') !== $user['id'])
                        <div class="shrink-0">
                            @livewire('user.follow-button', ['user' => $user])
                        </div>
                    @endif

                </div>




                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Joined {{ \Carbon\Carbon::parse($user['created_at'] ?? now())->format('d M Y') }}
                </div>
            </div>

            <!-- Buttons -->
            <div class="sm:pr-4">
                @if(session()->has('user') && session('user.id') === $user['id'])
                    <a href="{{ route('profile.edit', $user['username']) }}"
                        class="inline-flex items-center gap-2 border border-gray-400 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 px-4 py-2 rounded-full text-sm font-semibold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.651 1.651a2.12 2.12 0 010 3.001L9.015 18.637l-3.524.782a.5.5 0 01-.6-.6l.782-3.524 9.498-9.498a2.12 2.12 0 013.001 0z" />
                        </svg>
                        Edit Profile
                    </a>
                @else
                    <div class="flex gap-2">
                        <button
                            class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                            </svg>
                            Message
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabs -->
        <div class="mt-10 pt-6  border-gray-300 dark:border-gray-700 transition-all duration-300">
            <!-- Tab Buttons -->
            <div class="flex justify-center sm:justify-around border-b border-gray-300 dark:border-gray-700 mb-6">
                <button
                    class="tab-btn px-4 sm:px-6 py-2 text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500 transition-all duration-200 active"
                    data-tab="posts">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z" />
                        </svg>
                        <span>Posts</span>
                    </div>
                </button>

                <button
                    class="tab-btn px-4 sm:px-6 py-2 text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500 transition-all duration-200"
                    data-tab="reels">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.868v4.264a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Reels</span>
                    </div>
                </button>
            </div>


            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Posts -->

                <div id="tab-posts" class="tab-content  ">
                    @livewire('post.index', ['user_id' => $user['id']])

                </div>

                <!-- Reels -->
                <div id="tab-reels" class="tab-content hidden">
                    <div
                        class="bg-gray-100 dark:bg-gray-800 rounded-xl p-6 text-gray-800 dark:text-gray-200 text-center shadow-inner">
                        <h4 class="text-lg font-semibold mb-2">Reels</h4>
                        <p class="text-gray-500 dark:text-gray-400">No reels uploaded yet.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Script -->
        <script>
            document.querySelectorAll(".tab-btn").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const tab = btn.dataset.tab;

                    // Reset all
                    document.querySelectorAll(".tab-btn").forEach((b) => b.classList.remove("active", "border-blue-500", "text-blue-600", "dark:text-blue-400"));
                    document.querySelectorAll(".tab-content").forEach((c) => c.classList.add("hidden"));

                    // Activate selected
                    btn.classList.add("active", "border-blue-500", "text-blue-600", "dark:text-blue-400");
                    document.getElementById(`tab-${tab}`).classList.remove("hidden");
                });
            });
        </script>


    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userId = "{{ $user['id'] }}";
            const postsContainer = document.getElementById('posts-container');

            // Fetch user posts
            fetch(`/api/users/${userId}/posts`)
                .then(res => res.json())
                .then(data => {
                    postsContainer.innerHTML = data.posts.map(p => `
                                                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
                                                                        ${p.image ? `<img src="${p.image}" class="w-full h-48 object-cover rounded-t-lg" alt="Post">` : ''}
                                                                        <div class="p-3">
                                                                            <p class="text-gray-700 dark:text-gray-300 text-sm">${p.caption ?? ''}</p>
                                                                        </div>
                                                                    </div>
                                                                `).join('');
                });

            // Follow / Unfollow
            const followBtn = document.getElementById('follow-btn');
            if (followBtn) {
                followBtn.addEventListener('click', () => {
                    const action = followBtn.textContent.trim() === 'Follow' ? 'follow' : 'unfollow';
                    fetch(`/api/users/${userId}/${action}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status) {
                                followBtn.textContent = action === 'follow' ? 'Following' : 'Follow';
                            }
                        });
                });
            }
        });
    </script>
@endpush