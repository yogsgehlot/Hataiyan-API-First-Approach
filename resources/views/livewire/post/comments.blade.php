<div class="space-y-4">

    <!-- Add Comment -->
    <div class="flex items-center gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">

        <input type="text" wire:model="text" placeholder="Add a comment…" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full
                   px-4 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:outline-none">

        <button wire:click="add" class="text-blue-600 dark:text-blue-400 text-sm font-semibold hover:underline">
            Post
        </button>

    </div>
    
    <!-- View Comments Button -->
    @if ($post['comments_count']?? 0)
        <button wire:click="toggleComments"
            class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            @if ($showComments)
                Hide comments
            @else
                View all {{ $post['comments_count'] }} comments
            @endif
        </button>
    @endif

    <!-- Comment List (Only if Open) -->
    @if ($showComments)

        <div class="space-y-3">
            @foreach($post['comments'] as $comment)
                @livewire('post.comment-item', ['comment' => $comment], key('comment_' . $comment['id']))
            @endforeach
        </div>
    @endif




</div>