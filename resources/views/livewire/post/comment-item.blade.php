<div class="border-l-2 border-gray-200 dark:border-gray-700 pl-3">

    <!-- Comment -->

    <div class="flex items-start gap-2">
        <img src="{{ $comment['user']['avatar_path'] ?? asset('images/default-avatar.jpg') }}"
            class="w-8 h-8 rounded-full object-cover border">

        <div class="flex flex-col">

            <p class="text-sm">
                <strong class="text-gray-900 dark:text-gray-200">
                    {{ $comment['user']['name'] }}
                </strong>
                <span class="text-gray-700 dark:text-gray-400">

                    {!! formatCaption($comment['body']) !!}
                </span>
            </p>

            <!-- Reply Button -->
            <div class="flex gap-4 mt-1">
                <button class="text-xs text-blue-600 dark:text-blue-400" wire:click="$toggle('showBox')">
                    Reply
                </button>

                <!-- Show Replies Toggle -->
                @if ($comment['replies_count'])
                    <button class="text-xs text-gray-500 dark:text-gray-400" wire:click="toggleReplies">
                        @if ($showReplies)
                            Hide replies
                        @else
                            View replies ({{ $comment['replies_count']}})
                        @endif
                    </button>
                @endif
            </div>

        </div>
    </div>


    <!-- Reply Input Box -->
    @if($showBox)
        <div class="mt-2 ml-10 flex items-center gap-2">
            <input type="text" wire:model="reply" placeholder="Add a reply..." class="w-full rounded-full border border-gray-300 dark:border-gray-700
                           bg-gray-100 dark:bg-gray-800 px-3 py-1 text-sm">

            <button wire:click="sendReply" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-full hover:bg-blue-700">
                Send
            </button>
        </div>
    @endif


    <!-- Replies Section -->
    @if ($showReplies)
        <div class="mt-3 ml-10 space-y-2 border-l border-gray-200 dark:border-gray-700 pl-4">
            @foreach($comment['replies'] as $reply)
                @livewire('post.reply-item', ['reply' => $reply], key('reply_' . $reply['id']))
            @endforeach
        </div>
    @endif

</div>