<div>
    @if (auth()->id() !== $user['id'])
        <button 
            wire:click="toggleFollow"
            class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200
                @if($isFollowing)
                    border border-red-500 text-red-500 hover:bg-red-500 hover:text-white
                @else
                    bg-blue-600 text-white hover:bg-blue-700
                @endif
            ">
            {{ $isFollowing ? 'Unfollow' : 'Follow' }}
        </button>
    @endif
</div>
