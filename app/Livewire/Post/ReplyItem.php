<?php

namespace App\Livewire\Post;

use Livewire\Component;

class ReplyItem extends Component
{
    public $reply;   // reply is an ARRAY from API

 

    public function mount($reply)
    {
        $this->reply = $reply;
    }

     

    public function render()
    {
        return view('livewire.post.reply-item');
    }
}
