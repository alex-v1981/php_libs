<?php

class Log
{
    private $filePath;

    function __construct( $filePath )
    {
        $this->filePath = $filePath;
    }

    public function addLine( $text )
    {
        $line = date("d.m.Y H:i:s")."  ".$text."\n";

        $f = @fopen($this->filePath, "at");

        if ( $f ) {
            fputs($f, $line);
            fclose($f);
        }
    }
}