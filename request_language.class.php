<?php

class RequestLanguage
{
    static function get()
    {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            return 'en';

        $lng = strtolower( substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) );

        if ($lng != 'ru')
            return 'en';

        return 'ru';
    }
}