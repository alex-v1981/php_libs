<?php

class Locking
{
    private static function filePath()
    {
        return dirname(__FILE__).'/.lock';
    }

    public static function lock()
    {
        $f = fopen(Locking::filePath(), 'wb');
        fclose( $f );
    }

    public static function unlock()
    {
        @unlink( Locking::filePath() );
    }

    public static function isLock()
    {
        return file_exists( Locking::filePath() );
    }
}