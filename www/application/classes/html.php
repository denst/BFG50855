<?

class HTML extends Kohana_HTML
{
    public static function DecodeChars($subject)
    {
        return str_replace('&quot;', '"', $subject);
    }
    
    public static function clear($text)
    {
        $text = str_replace('"', '', $text);
        $text = str_replace('«', '', $text);
        $text = str_replace("'", "", $text);
        $text = str_replace("`", "", $text);
        $text = str_replace('»', '', $text);
		$text = str_replace('&quot;', '', $text);
        return trim($text);

    }

}

