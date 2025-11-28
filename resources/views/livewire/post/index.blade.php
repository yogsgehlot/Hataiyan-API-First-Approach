<div class="flex flex-col gap-8 justify-center px-2 py-10 flex-wrap">
    @foreach($posts as $post)
        <livewire:post.card :post="$post" :key="$post['id']" />
    @endforeach

</div>