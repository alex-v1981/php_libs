<?php

class Parser
{
    public function __construct()
    {
        ini_set("pcre.backtrack_limit", 10000000);
    }

    public function parseElementWithPattern($text, $pattern, $index=0, $caseInsensitive=false)
    {
        $pattern = '/'.$pattern.'/s';

        if ($caseInsensitive)
            $pattern .= 'i';

        if (preg_match($pattern, $text, $res) && isset($res[$index]))
            return $res[$index];

        return false;
    }

    public function parseElementWithTags($text, $beginTagPattern, $endTagPattern, $caseInsensitive=false, $excludeChars=false)
    {
        $pcs = $excludeChars ? '[^'.$excludeChars.']' : '.';
        $pattern = '/('.$this->preparePattern( $beginTagPattern ).')('.$pcs.'*?)('.$this->preparePattern( $endTagPattern ).')/s';

        if ( $caseInsensitive )
            $pattern .= 'i';

        if (preg_match($pattern, $text, $res) && count($res)>=3)
            return $res[2];

        return false;
    }

    public function parseAllElementsWithTags($text, $beginTagPattern, $endTagPattern, $caseInsensitive=false, $excludeChars=false)
    {
        $pcs = $excludeChars ? '[^'.$excludeChars.']' : '.';
        $pattern = '/('.$this->preparePattern( $beginTagPattern ).')('.$pcs.'*?)('.$this->preparePattern( $endTagPattern ).')/s';

        if ( $caseInsensitive )
            $pattern .= 'i';

        if (preg_match_all($pattern, $text, $res) && count($res)>=3)
            return $res[2];

        return false;
    }

    public function parseAllElementsWithPattern($text, $pattern, $index=0, $caseInsensitive=false)
    {
        $pattern = '/'.$pattern.'/s';

        if ($caseInsensitive)
            $pattern .= 'i';

        if (preg_match_all($pattern, $text, $res) && isset($res[$index]))
            return $res[$index];

        return false;
    }

    public function parseSplitElements($text, $splitterPattern, $noEmpty=false)
    {
        $pattern = '/'.$this->preparePattern( $splitterPattern ).'/s';
        $flag = $noEmpty ? PREG_SPLIT_NO_EMPTY : 0;

        return preg_split($pattern, $text, -1, $flag);
    }

    private function preparePattern( $pattern )
    {
        $pattern = str_replace("/", "\\/", $pattern);
        $pattern = str_replace("|", "\\|", $pattern);

        return $pattern;
    }
}