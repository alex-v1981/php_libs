<?php

class Http
{
    const DEFAULT_USER_AGENT = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.104 Safari/537.36";

    private $timeOut;
    private $proxy;
    private $delayAfterQuery;
    private $userAgent;
    private $rememberCookies = false;
    private $headers = array();
    private $referer = false;

    function __construct($timeOut=30, $proxy=false, $delayAfterQuery=0, $userAgent=false)
    {
        $this->timeOut = $timeOut;
        $this->proxy = $proxy;
        $this->delayAfterQuery = $delayAfterQuery;
        $this->userAgent = $userAgent ? $userAgent : self::DEFAULT_USER_AGENT;
    }

    private function initQuery( $url )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeOut);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if ( $this->rememberCookies )
        {
            curl_setopt($ch, CURLOPT_COOKIEFILE, './cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEJAR, './cookie.txt');
        }

        $headers = $this->headers;

        if ( $this->referer )
            $headers = array_merge($headers, array("Referer: ".$this->referer));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ( $this->proxy )
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy);

        return $ch;
    }

    private function getQueryResult( $ch )
    {
        $data = curl_exec($ch);
        curl_close($ch);

        if ( $this->delayAfterQuery )
            sleep( $this->delayAfterQuery );

        return $data;
    }

    public function setRememberCookies( $remeberCookies )
    {
        $this->rememberCookies = $remeberCookies;
    }

    public function setHeaders( $headers )
    {
        $this->headers = $headers;
    }

    public function setProxy( $proxy )
    {
        $this->proxy = $proxy;
    }

    public function setUserAgent( $userAgent )
    {
        $this->userAgent = $userAgent;
    }

    public function setReferer( $referer )
    {
        $this->referer = $referer;
    }

    public function post($url, $params=false)
    {
        $ch = $this->initQuery( $url );
        curl_setopt($ch, CURLOPT_POST, true);

        if ( $params )
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        return $this->getQueryResult( $ch );
    }

    public function get( $url )
    {
        $ch = $this->initQuery( $url );
        return $this->getQueryResult( $ch );
    }

    public function downloadFileToLocalPath($url, $path, $overwrite=false)
    {
        if ( file_exists( $path ) )
        {
            if ( !$overwrite )
                return false;

            unlink( $path );

            if ( file_exists( $path ) )
                return false;
        }

        $data = $this->get( $url );

        if ( $data===false )
            return false;

        $f = fopen($path, 'wb');
        fwrite($f, $data);
        fclose( $f );

        return file_exists( $path );
    }
}