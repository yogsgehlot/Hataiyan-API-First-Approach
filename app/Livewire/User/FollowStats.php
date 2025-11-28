<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Services\ApiService;

class FollowStats extends Component
{
    public $user;
    public $showFollowers = false;
    public $showFollowing = false;

    public $followers = [];
    public $following = [];

    protected ApiService $api;

    protected $listeners = ['followUpdated' => 'reloadStats'];

    /**
     * Auto-inject ApiService BEFORE mount() runs
     */
    public function boot(ApiService $api)
    {
        $this->api = $api;
    }

    public function mount($user)
    {
        $this->user = $user;

        // Load initial stats (optional)
        $this->reloadStats();
    }

    public function reloadStats()
    {
        $this->followers = $this->api
            ->get("follow/{$this->user['id']}/followers")['data'];

        $this->following = $this->api
            ->get("follow/{$this->user['id']}/following")['data'];

    }


    public function showFollowersModal()
    {
        $this->reloadStats();
        $this->showFollowers = true;
    }

    public function showFollowingModal()
    {

        $this->reloadStats();
        $this->showFollowing = true;
    }

    public function closeModal()
    {
        $this->reset(['showFollowers', 'showFollowing']);
    }

    public function render()
    {
        return view('livewire.user.follow-stats');
    }
}
