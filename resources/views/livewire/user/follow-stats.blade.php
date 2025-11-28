<div class="w-full">

    <!-- Followers & Following -->
    <div class="flex items-center space-x-5">
        <!-- Followers -->
        <div class="text-center cursor-pointer select-none" wire:click="showFollowersModal">
            <span class="block text-lg font-semibold text-gray-900 dark:text-gray-200">
                {{ count($followers) }}
            </span>
            <span class="text-sm text-gray-600 dark:text-gray-400">Followers</span>
        </div>

        <!-- Following -->
        <div class="text-center cursor-pointer select-none" wire:click="showFollowingModal">
            <span class="block text-lg font-semibold text-gray-900 dark:text-gray-200">
                {{ count($following) }}
            </span>
            <span class="text-sm text-gray-600 dark:text-gray-400">Following</span>
        </div>
    </div>



    <!-- Followers Modal -->
    @if($showFollowers)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-96 max-h-[80vh] overflow-hidden">

                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Followers</h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                </div>

                <div class="overflow-y-auto max-h-[65vh] px-4 py-3 space-y-3">
                    @forelse($followers as $follower)
                        <div
                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">

                            <a href="{{ route('profile.view', $follower['username']) }}" class="flex items-center gap-3">
                                <img src="{{ $follower['avatar'] ?? asset('images/default-avatar.jpg') }}"
                                    class="w-12 h-12 rounded-full object-cover border border-gray-300 dark:border-gray-700" />

                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $follower['name'] }}
                                </span>
                            </a>
                            @if(session('user.id') !== $follower['id'])
                                @livewire('user.follow-button', ['user' => $follower], key('follower-' . $follower['id']))
                            @endif

                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-3">No followers yet.</p>
                    @endforelse
                </div>

            </div>
        </div>
    @endif



    <!-- Following Modal -->
    @if($showFollowing)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-96 max-h-[80vh] overflow-hidden">

                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Following</h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                </div>

                <div class="overflow-y-auto max-h-[65vh] px-4 py-3 space-y-3">
                    @forelse($following as $follow)

                        <div
                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">

                            <a href="{{ route('profile.view', $follow['username']) }}" class="flex items-center gap-3">
                                <img src="{{ $follow['avatar'] ?? asset('images/default-avatar.jpg') }}"
                                    class="w-12 h-12 rounded-full object-cover border border-gray-300 dark:border-gray-700" />

                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $follow['name'] }}
                                </span>
                            </a>
                            @if(session('user.id') !== $follow['id'])
                                @livewire('user.follow-button', ['user' => $follow], key('following-' . $follow['id']))
                            @endif


                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-3">Not following anyone yet.</p>
                    @endforelse
                </div>

            </div>
        </div>
    @endif

</div>