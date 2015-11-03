<?php

class RequestLanguage
{
    static function get()
    {
        $lng = strtolower( substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) );

        if ($lng!='ru')
            $lng = 'en';

        return $lng;
    }
}