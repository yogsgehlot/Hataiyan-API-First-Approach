<?php

if (!function_exists('parseMentions')) {
    function parseMentions($text)
    {
        return preg_replace(
            '/@([A-Za-z0-9_]+)/',
            '<a href="/profile/$1" class="text-blue-500 hover:underline">@${1}</a>',
            $text
        );
    }
}

if (!function_exists('parseHashtags')) {
    function parseHashtags($text)
    {
        return preg_replace(
            '/#([A-Za-z0-9_]+)/',
            '<a href="/hashtag/$1" class="text-blue-500 hover:underline">#${1}</a>',
            $text
        );
    }
}

if (!function_exists('formatCaption')) {
    function formatCaption($text)
    {
        $text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
  
        $text = nl2br($text);         // convert newlines
        $text = parseMentions($text); // convert @user
        $text = parseHashtags($text); // convert #tag

        return $text;
    }
}
