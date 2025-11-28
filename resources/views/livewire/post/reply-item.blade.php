<div class="flex items-start gap-3">

 
    <!-- Avatar -->
    <img src="{{ $reply['user']['avatar_path'] ?? asset('images/default-avatar.jpg') }}"
         class="w-7 h-7 rounded-full object-cover border border-gray-300 dark:border-gray-700">

    <!-- Content -->
    <div class="flex flex-col">

        <p class="text-sm leading-snug">
            <strong class="text-gray-900 dark:text-gray-100">
                {{ $reply['user']['name'] }}
            </strong>

            <span class="ml-1 text-gray-700 dark:text-gray-300">
      
                {!! formatCaption($reply['body']) !!}
            </span>
        </p>

        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ \Carbon\Carbon::parse($reply['created_at'])->diffForHumans() }}
        </span>

    </div>

</div>
