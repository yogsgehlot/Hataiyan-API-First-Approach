<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\MentionNotification;

class MentionService
{
    public function handleMentions(string $text, $fromUser, string $context, $contextId = null)
    {
        preg_match_all('/@([A-Za-z0-9_]+)/', $text, $matches);
        $usernames = $matches[1] ?? [];

        if (empty($usernames)) return;

        $users = User::where('username', $usernames)->get();
         
        foreach ($users as $user) {
            if ($user->id === $fromUser->id) continue;
        
            $user->notify(new MentionNotification($fromUser, $context, $contextId));
        }
    }
}
