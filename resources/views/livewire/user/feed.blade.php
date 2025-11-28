<div>


    {{-- POSTS --}}
    @foreach($posts as $post)
        <div class="my-3">
            <livewire:post.card :post="$post" :key="'post-' . $post['id'] . '-' . microtime(true)" />
        </div>
    @endforeach


    {{-- AUTO INFINITE SCROLL TRIGGER --}}
    @if($hasMore)
    
        <div class="infinite-loader" wire:click="loadMore" wire:poll.visible="loadMore">
            <div class="loader"></div>
            <span>Loading more...</span>
        </div>

    @else
        <div class="text-center py-3 text-muted">
            No more posts
        </div>
    @endif

</div>