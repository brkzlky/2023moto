<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function limit_string($string, $number = 20)
    {
        $clean_string = trim(strip_tags(substr(($string), 0, 20)));
        if (strlen($clean_string) >= 20) {
            return $clean_string . '...';
        } else {
            return $clean_string;
        }
    }
}
