<div class="flex items-center justify-start  gap-2">

    {{-- Like Button (Heart Animation) --}}
    <button wire:click="toggleLike">
        <span class="text-2xl transition transform hover:scale-110
            {{ $isLiked ? 'text-red-600' : 'text-gray-400 dark:text-gray-600' }}">
            ♥
        </span>

    </button>
    {{-- Likes Count --}}
    <span class="text-gray-700 dark:text-gray-300 text-sm font-medium">
        {{ $likes }} likes
    </span>


</div>