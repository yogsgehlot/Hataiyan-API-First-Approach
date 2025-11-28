<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Services\ApiService;

class Comments extends Component
{
    public $post;
    public $text = '';
    public $showComments = false;

    protected $listeners = ['postUpdated' => 'updatePost'];
    protected $api;

    public function boot(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function mount($post)
    {
        $this->post = $post; // API array
    }

    /**
     * Add a new comment through API
     */
    public function add()
    {
        if (trim($this->text) === '') {
            return;
        }

        $payload = [
            'user_id' => session('user.id'),
            'post_id' => $this->post['id'],
            'comment' => $this->text,
        ];

        $response = $this->api->post("posts/{$this->post['id']}/comment", $payload);

        if ($response['success']) {
            $this->text = '';
            $this->showComments = true; // Reveal list after posting
            $this->dispatch('refreshPost', postId: $this->post['id'])
                ->to('post.card');
        }
    }


    public function updatePost($newPost)
    {
        // dd($newPost);die;
        if ($newPost['id'] != $this->post['id']) {
            return; // ignore event from other posts
        }
        $this->post = $newPost;
    }

    /**
     * Toggle comment list
     */
    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function render()
    {
        return view('livewire.post.comments');
    }
}
