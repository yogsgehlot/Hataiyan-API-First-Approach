<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use App\Services\ApiService;

class Card extends Component
{
    public $post;
    public $showMenu = false;

    protected $listeners = ['refreshPost' => 'reloadPost'];

    protected $api;

    public function boot(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function mount($post)
    { 
        $this->loadPost($post['id']);
    }

    public function loadPost($postId)
    {

        $response = $this->api->get("posts/$postId");
        // dd(Post::withExists(['user'])->first());

        //  dd($response);die;
        if ($response['success']) {
            $this->post = $response['data'];
        }
    }

    public function reloadPost($postId)
    {
        if ($postId != $this->post['id']) {
            return; // Not for this card
        }

        $this->loadPost($postId);

        $this->dispatch('postUpdated', $this->post);
    }

    public function toggleMenu()
    {
        $this->showMenu = !$this->showMenu;
    }

    public function render()
    {
        return view('livewire.post.card');
    }
}
