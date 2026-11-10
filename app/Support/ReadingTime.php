<?php

namespace App\Support;

class ReadingTime
{
    /**
     * Average word length (in characters) of a post body, shown alongside the reading-time estimate.
     * Returns null when the body contains no words (e.g. an image-only post).
     */
    public static function averageWordLength(string $html): ?int
    {
        $text = trim(strip_tags($html));
        $words = str_word_count($text);

        if ($words === 0) {
            return null;
        }

        return intdiv(strlen(str_replace(' ', '', $text)), $words);
    }
}
