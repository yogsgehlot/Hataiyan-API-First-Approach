<?php
namespace App\Livewire\User;

use Livewire\Component;
use App\Services\ApiService;
use Auth;

class FollowList extends Component
{
    public $profileUserId;
    public $tab = 'followers';
    public $list = [];

    protected $listeners = ['followersUpdated' => 'loadList'];

    public function mount($profileUser)
    {
        $this->profileUserId = $profileUser->id;
        $this->loadList();
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
        $this->loadList();
    }

    public function loadList()
    {
        $api = new ApiService();

        if ($this->tab == 'followers') {
            $response = $api->get("follow/{$this->profileUserId}/followers");
        } else {
            $response = $api->get("follow/{$this->profileUserId}/following");
        }

        $this->list = $response['data'];
    }

    public function toggleFollowFromList($targetUserId)
    {
        $api = new ApiService();

        // toggle follow
        $api->post('follow/toggle', [
            'user_id' => $targetUserId
        ]);

        // reload updated list
        $this->loadList();

        // update follow button on the profile
        $this->dispatch('followersUpdated');
    }

    public function render()
    {
        return view('livewire.user.follow-list');
    }
}
