<?php

class Formatter
{
    public static function formatResponse($text)
    {
        $text = preg_replace('/```(\w+)?\n(.*?)\n```/s', '<pre><code>$2</code></pre>', $text);
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
        $text = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $text);
        $text = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $text);

        return $text;
    }
}
