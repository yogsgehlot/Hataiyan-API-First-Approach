<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Services\ApiService;

class Index extends Component
{
    public $posts = [];
    protected $api;

    public $user_id;

    protected $listeners = ['removePost'];

    public function boot(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function mount($user_id)
    {   $this->user_id = $user_id;
        $this->loadPosts();
    }

    public function loadPosts()
    {
         

        if (!$this->user_id) {
            $this->posts = [];
            return;
        }

        // Call API
        $response = $this->api->get("posts/", [
            'user_id' => $this->user_id,
        ]);
        
        if ($response['success'] === true) {
            $this->posts = collect($response['data']);
        }
    }

    public function removePost($postId)
    {
        // Remove post locally from the list
        $this->posts = $this->posts
            ->filter(fn($post) => $post['id'] != $postId)
            ->values();
    }

    public function render()
    {
        return view('livewire.post.index');
    }
}
