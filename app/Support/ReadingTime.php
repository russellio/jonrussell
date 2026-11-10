<?php

namespace App\Support;

class ReadingTime
{
    /**
     * Average word length (in characters) of a post body, shown alongside the reading-time estimate.
     */
    public static function averageWordLength(string $html): int
    {
        $text = trim(strip_tags($html));

        return intdiv(strlen(str_replace(' ', '', $text)), str_word_count($text));
    }
}
