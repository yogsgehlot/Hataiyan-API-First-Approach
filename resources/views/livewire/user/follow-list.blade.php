<div class="p-4">
    <div class="flex gap-4 border-b mb-3 pb-2">
        <button wire:click="changeTab('followers')" class="{{ $tab=='followers'?'font-bold':'' }}">
            Followers
        </button>
        <button wire:click="changeTab('following')" class="{{ $tab=='following'?'font-bold':'' }}">
            Following
        </button>
    </div>

    <div class="space-y-3">
        @foreach ($list as $user)
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ $user['avatar'] }}" class="w-10 h-10 rounded-full">
                    <div>{{ $user['name'] }}</div>
                </div>

                <button
                    wire:click="toggleFollowFromList({{ $user['id'] }})"
                    class="px-3 py-1 rounded text-sm 
                        {{ auth()->id() == $user['id'] ? 'hidden' : (auth()->user()->isFollowing($user['id']) ? 'bg-gray-300' : 'bg-blue-600 text-white') }}">
                    {{ auth()->user()->isFollowing($user['id']) ? 'Unfollow' : 'Follow' }}
                </button>
            </div>
        @endforeach
    </div>
</div>
