<div class="  w-full  ">

    <div
        class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm overflow-hidden transition hover:shadow-md">

        <!-- Header -->
        <div class="relative flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">

                <img src="{{ $post['user']['avatar_path'] ?? asset('images/default-avatar.jpg') }}"
                    class="w-10 h-10 rounded-full border border-gray-300 dark:border-gray-700 object-cover">

                <div>
                    <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm">
                        {{ $post['user']['name'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">

                {{-- ============================ --}}
                {{-- FOLLOW BUTTON --}}
                {{-- Show only if not the same user --}}
                @if(session('user.id') !== $post['user']['id'])

                    <div class="shrink-0">
                        @livewire('user.follow-button', ['user' => $post['user']])
                    </div>

                @endif
                {{-- ============================ --}}

                <!-- 3-dot menu button -->
                <button wire:click="toggleMenu"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                    </svg>
                </button>

            </div>

            <!-- Dropdown Menu -->

            @if ($showMenu)
                <div class="absolute right-3 top-12 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 animate-fade-in "
                    x-data @click.outside="$wire.set('showMenu', false)">

                    @if (session('user.id') === $post['user_id'])

                        <!-- Edit button -->
                        <a href="{{ route('post.edit', ['id' => $post['id']]) }}"
                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">

                            <!-- Pencil Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 3.487a2.121 2.121 0 113 3L7.5 18.75l-4 1 1-4 12.362-12.263z" />
                            </svg>

                            Edit
                        </a>

                        <!-- Delete button -->
                        <form action="{{ route('post.delete', $post['id']) }}" method="POST"
                            onsubmit="return confirm('Delete this post?')" class="w-full">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400
                                                                                   hover:bg-gray-100 dark:hover:bg-gray-700 text-left">

                                <!-- Trash icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 7h12M9 7v10m6-10v10M4 7h16l-1 13H5L4 7zm5-3h6v3H9V4z" />
                                </svg>

                                Delete
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('report.post.form', $post['id']) }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <!-- icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path fill="none" stroke="currentColor" stroke-width="2"
                                d="M12 9v2m0 4h.01M5 3h14l-1.16 13.28A2 2 0 0115.85 18H8.15a2 2 0 01-1.99-1.72L5 3z" />
                        </svg>
                        Report
                    </a>

                </div>
            @endif
        </div>

        <!-- Caption -->
        <div class="px-4 pb-3 py-2">
            <p class="text-gray-800 dark:text-gray-300 text-sm leading-relaxed">
                {!! formatCaption($post['caption']) !!}
            </p>
        </div>

        <!-- Media -->
        @if ($post['media_path'])
            <div class="px-4 pb-4">

                {{-- IMAGE --}}
                @if ($post['media_type'] === 'image')
                    <div class="w-full">
                        <div class="{{ $post['aspect_ratio_class'] }} overflow-hidden rounded-lg">
                            <img src="{{ $post['media_path'] }}" class="w-full h-full object-cover" />
                        </div>
                    </div>
                @endif

                {{-- VIDEO (supports both 9:16 and 16:9 automatically) --}}
                @if ($post['media_type'] === 'video')
                    <div class="w-full">
                        {{-- BEST UI for mixed video types --}}
                        <div class="aspect-video overflow-hidden rounded-lg">
                            <video src="{{ $post['media_path'] }}" class="w-full h-full" controls playsinline autoplay loop>
                            </video>
                        </div>
                    </div>
                @endif

            </div>
        @endif

        <!-- Actions -->
        <div class="px-4 pt-3 pb-1">
            @livewire('post.actions', ['post' => $post], key('actions_' . $post['id']))
        </div>

        <!-- Comments Section -->
        <div class="px-4 pb-4">
            @livewire('post.comments', ['post' => $post], key('comments_' . $post['id']))
        </div>

    </div>

</div>