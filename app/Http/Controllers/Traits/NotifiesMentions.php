<?php
namespace App\Http\Controllers\Traits;

use App\Models\User;

trait NotifiesMentions
{
    /**
     * Parse text for @username mentions and return unique User collection
     */
    protected function findMentionedUsers(string $text)
    {
        preg_match_all('/@([\w\-]+)/', $text, $matches);
        $usernames = array_unique($matches[1] ?? []);
        if (empty($usernames))
            return collect();

        return User::whereIn('username', $usernames)->get();
    }

    /**
     * Notify mentioned users (skips actor)
     * $context = ['post_id'=>..., 'comment_id'=>..., 'excerpt'=>...]
     */
    protected function notifyMentionsForText($text, $actor, $notifications, array $context = [])
    {
        $mentioned = $this->findMentionedUsers($text);
        foreach ($mentioned as $user) {
            if ($actor->id === $user->id)
                continue; // don't notify self
            $notifications->create(
                $user,
                'mention',
                array_merge($context, ['mentioned_by_id' => $actor->id]),
                $actor
            );
        }
    }
}
