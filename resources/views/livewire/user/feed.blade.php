<div>
    @foreach($posts as $post)
        <div class="my-3">
            <livewire:post.card :post="$post" :key="'post-' . $post['id']" />
        </div>
    @endforeach

    @if($hasMore)
        <div id="scrollTrigger" class="infinite-loader">
            <div class="loader"></div>
            <span>Loading more...</span>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    const trigger = document.getElementById('scrollTrigger');

    if (!trigger) return;

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            window.dispatchEvent(new CustomEvent('load-more'));
        }
    }, { threshold: 0.4 });

    observer.observe(trigger);
});
</script>
@endpush
