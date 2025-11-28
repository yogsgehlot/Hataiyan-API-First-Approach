<?php

namespace App\Livewire\Post;

use Livewire\Component;
use App\Services\ApiService;

class CommentItem extends Component
{
    public $comment;      // This is an API array, not a model
    public $reply = '';
    public $showBox = false;
    public $showReplies = false;

    protected $api;

    protected $listeners = ['postUpdated' => 'updateComment'];
    public function boot(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function mount($comment)
    {
        $this->comment = $comment;  // Array from API
    }

    /**
     * Send a reply using API
     */
    public function sendReply()
    {
        if (trim($this->reply) === '') {
            return;
        }

        $payload = [
            'user_id' => session('user.id'),
            'comment_id' => $this->comment['id'],
            'reply' => $this->reply,
            'post_id' => $this->comment['post_id']
        ];


        $response = $this->api->post("/comments/{$this->comment['id']}/reply", $payload);


        if ($response['success']) {
            $this->reply = '';
            $this->showBox = false;
            $this->showReplies = true;

            // Refresh only the parent post component
            $this->dispatch('refreshPost', postId: $this->comment['post_id'])
                ->to('post.card');
        }
    }

    public function updateComment($newPost)
    {
        // Find updated comment from new post data
        $updated = collect($newPost['comments'])
            ->firstWhere('id', $this->comment['id']);

        if ($updated) {
            $this->comment = $updated;   // refresh comment + replies
        }
    }

    public function toggleReplies()
    {
        $this->showReplies = !$this->showReplies;
    }

    public function render()
    {

        return view('livewire.post.comment-item');
    }
}
