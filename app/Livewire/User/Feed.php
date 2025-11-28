<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Services\ApiService;

class Feed extends Component
{

    public $posts = [];
    public $page = 1;
    public $hasMore = true;

    protected $api;

    public function boot(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    public function mount()
    {
        $this->loadMore();  // load first page
    }

    public function loadMore()  
    {
        if (!$this->hasMore)
            return;

        $response = $this->api->get("users/feed/" . $this->page);

        if ($response['success']) {
            $newPosts = $response['data']['posts']['data'];

            if (count($newPosts) > 0) {
                $this->posts = array_merge($this->posts, $newPosts);
                $this->page++;
            } else {
                $this->hasMore = false;
            }
        }
    }




    public function render()
    {
        return view('livewire.user.feed');
    }
}
