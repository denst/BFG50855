<?php

class Text extends Kohana_Text
{

    public static function date($date)
    {
        return date('d.m.Y', $date);
    }

    public static function time($date)
    {
        return date('H:i', $date);
    }

    public static function humanDate($date)
    {
        $date = date('j -n- Y', $date);
        $date = str_replace('-1-', 'января', $date);
        $date = str_replace('-2-', 'февраля', $date);
        $date = str_replace('-3-', 'марта', $date);
        $date = str_replace('-4-', 'апреля', $date);
        $date = str_replace('-5-', 'мая', $date);
        $date = str_replace('-6-', 'июня', $date);
        $date = str_replace('-7-', 'июля', $date);
        $date = str_replace('-8-', 'августа', $date);
        $date = str_replace('-9-', 'сентября ', $date);
        $date = str_replace('-10-', 'октября', $date);
        $date = str_replace('-11-', 'ноября ', $date);
        $date = str_replace('-12-', 'декабря ', $date);
        return $date . ' г.';
    }

    public static function humanDateTime($date)
    {
        return self::humanDate($date) . ' ' . self::time($date);
    }

    /**
     * Этот метод используется там, где логично поставить "столько-то часов назад", "вчера в столько-то" и т.д.
     * @param type $date
     * @return string
     */
    public static function msg_humanDate($date)
    {
        $d = time() - $date;
        $min = round($d / 60);
        if ($min < 1)
            return 'только что';
        if ($min < 60)
            return Text::chislitelnie($min, array('минуту', 'минуты', 'минут')) . ' назад ';
        $hr = round($d / (60 * 60));
        if ($hr < 24)
            return Text::chislitelnie($hr, array('час', 'часа', 'часов')) . ' назад в ' . date('H:i', $date);
        if ($d < 24 * 60 * 60)
            return 'сегодня в ' . date('H:i', $date);
        if ($d < 2 * 24 * 60 * 60)
            return 'вчера в ' . date('H:i', $date);
        $date = date('j -n- Y', $date);
        $date = str_replace('-1-', 'января', $date);
        $date = str_replace('-2-', 'февраля', $date);
        $date = str_replace('-3-', 'марта', $date);
        $date = str_replace('-4-', 'апреля', $date);
        $date = str_replace('-5-', 'мая', $date);
        $date = str_replace('-6-', 'июня', $date);
        $date = str_replace('-7-', 'июля', $date);
        $date = str_replace('-8-', 'августа', $date);
        $date = str_replace('-9-', 'сентября ', $date);
        $date = str_replace('-10-', 'октября', $date);
        $date = str_replace('-11-', 'ноября ', $date);
        $date = str_replace('-12-', 'декабря ', $date);
        return $date;
    }

    /* Возвращает номер дня */

    public static function day($date)
    {
        return date('d', $date);
    }

    /**
     * Возвращает существительное в правильном падеже в зависимости от номера
     * @param type $num номер
     * @param type $text массив падежей, например: array('рубль','рубля','рублей')
     * @return type
     */
    public static function chislitelnie($num, $text = array('балл', 'балла', 'баллов'))
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $num . " " . $text[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    /**
     * Преобразовать все кириллические символы в латинские. Все левые символы вырезает.
     * @param string $string Исходный текст
     * @return string Преобразованная строка
     */
    public static function RusToLat($string)
    {
        $tr = array(
            "А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
            "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
            "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
            "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
            "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
            "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "",
            "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
            " " => "_", "." => "", "/" => "_"
        );
        $string = strtr($string, $tr);
        $string = preg_replace('/[^A-Za-z0-9_\-]/', '', $string);
        //$string = mb_substr($string, 0, 250);
        return $string;
    }

    // Обрезает текст от всех переводов строк, а также заменяет пробелы на нормальные :)
    static function desc($text)
    {
        return strip_tags(str_replace('&nbsp;',' ',str_replace(array("\r\n", "\n", "\r"),'',  $text)));
    }

    static function addHttp($url)
    {
        if (mb_strlen($url) > 7)
        {
            if (mb_substr($url, 0, 7) != 'http://')
                $url = 'http://' . $url;
        }
        else
        {
            $url = 'http://' . $url;
        }
        return $url;
    }

	/*
	 * Возвращает объект для плеера Youtube
	 */
	public static function youtube($text)
	{
        // Делаем ютубовские ссылки ютубовскими видяшками.
        $regexp = '/http:\/\/(?:www\.)?youtu(?:be\.com\/(?:watch\?v=|v\/)|\.be\/)([^&]+).*$/';
        preg_match($regexp,$text,$matches);

        if (isset($matches[1]))
			$text = $matches[1];
		else
			$text = null;

        return $text;
	}

	/*
	 * Возвращает объект для плеера Youtube
	 */
	public static function youtube_embed($text)
	{
        // Делаем ютубовские ссылки ютубовскими видяшками.
        $regexp = '/http:\/\/(?:www\.)?youtu(?:be\.com\/(?:watch\?v=|v\/|embed\/)|\.be\/)([^&]+)".frameborder/';
        preg_match($regexp,$text,$matches);

        if (isset($matches[1]))
			$text = $matches[1];
		else
			$text = null;

        return $text;
	}



}