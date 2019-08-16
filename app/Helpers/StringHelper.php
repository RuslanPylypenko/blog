<?php


namespace App\Helpers;


class StringHelper
{
    public static function getHortText($text, $length){
        $short_text = mb_substr($text, 0, $length);
        $next_symbol = mb_substr($text, $length, 1);

        if($next_symbol != " "){
            $last_symbol = mb_strripos($short_text, " ");
            $short_text = mb_substr($text, 0, $last_symbol);
        }

        return  $short_text;
    }
}