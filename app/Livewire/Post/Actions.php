<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Services\ApiService;

class Actions extends Component
{
    public $post;
    public $isLiked;
    public $likes;

    protected $api;

    public function boot(ApiService $apiService)
    {
        $this->api = $apiService;
    }


    public function mount($post)
    { 
        $this->post = $post;
        $this->isLiked = $post['is_liked'] ?? false;   // API flag
        $this->likes = $post['likes_count'] ?? 0;
    }

    /**
     * Toggle Like
     */
    public function toggleLike()
    {
        $userId = session('user.id');

        $response = $this->api->post("posts/{$this->post['id']}/like", [
            'user_id' => $userId
        ]);

        if ($response['data']['status']) {
            $this->isLiked = $response['data']['is_liked'];
            $this->likes = $response['data']['likes_count'];
        }

        // Tell only this post card to refresh
        $this->dispatch('refreshPost', postId: $this->post['id'])
            ->to('post.card');
    }

    /**
     * Delete Post
     */
    public function deletePost()
    {
        $response = $this->api->delete("posts/delete/{$this->post['id']}");

        if ($response['status']) {
            // Remove from UI instantly
            $this->dispatch('removePost', postId: $this->post['id'])
                ->to('post.index');
        }
    }


    public function render()
    {
        return view('livewire.post.actions');
    }
}
