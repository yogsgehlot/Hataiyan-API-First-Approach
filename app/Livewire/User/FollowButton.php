<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Services\ApiService;
use Illuminate\Support\Facades\Auth;

class FollowButton extends Component
{
    public $user;
    public $isFollowing = false;

    protected ApiService $api;

    /**
     * BOOT METHOD  → fires before mount()
     */
    public function boot(ApiService $api)
    {
        // ApiService is auto-injected by Laravel's container
        $this->api = $api;
    }

    // public function mount($user)
    // {
    //     $this->user = $user;

    //     // load followers list via injected ApiService
    //     $followers = $this->api->get("follow/{$this->user['id']}/followers");

    //     $this->isFollowing = collect($followers['data'])
    //         ->pluck('id')
    //         ->contains(Auth::id());



    // }

    public function mount($user)
    {
        $this->user = $user;

        // Logged-in user's following list
        $authId = session('user.id');
        $following = $this->api->get("follow/{$authId}/following");

        // Check if auth user follows this user
        $this->isFollowing = collect($following['data'])
            ->pluck('id')
            ->contains($this->user['id']);
    }


    // public function toggleFollow()
    // {
    //     $response = $this->api->post("follow/toggle", [
    //         'user_id' => $this->user['id']
    //     ]);
    //     $this->isFollowing = $response['data']['status'] === 'followed';

    //     // Emit event to refresh FollowStats component
    //     $this->dispatch('followUpdated')->to(FollowStats::class);
    // }

    public function toggleFollow()
    {
        $response = $this->api->post("follow/toggle", [
            'user_id' => $this->user['id']
        ]);

        // refresh following list for logged-in user
        $authId = session('user.id');
        $following = $this->api->get("follow/{$authId}/following");

        $this->isFollowing = collect($following['data'])
            ->pluck('id')
            ->contains($this->user['id']);

        // notify stats component
        $this->dispatch('followUpdated')->to(FollowStats::class);
    }


    public function render()
    {
        return view('livewire.user.follow-button');
    }
}
