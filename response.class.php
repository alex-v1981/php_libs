<?php

class Response
{
    public static function responseToClient( $data )
    {
        exit( json_encode( $data ) );
    }

    public static function error( $msg )
    {
        self::responseToClient( array('error'=>$msg) );
    }

    public static function errorWithCode($code, $description)
    {
        self::responseToClient( array('error'=>$code, 'description'=>$description) );
    }
}