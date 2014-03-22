<?php

defined('SYSPATH') or die('No direct script access.');

class Arr extends Kohana_Arr {

    /**
     * Возвратить элементы полученного массива через запятую
     * @param array $mass Массив данных
     * @param string $ifnull Возвращает это, если придет пустой массив
     * @param string $glue Что писать между элементами массива (по умолчанию ", ")
     * @return string
     */
    public static function ListArray($mass, $ifnull = '', $glue = ', ')
    {
        if (empty($mass))
            return $ifnull;
        return implode($glue, $mass);
    }

    /**
     * Отобрать из указанного ассоциативного массива указанные элементы
     * @param array $items Массив элементов, которые следуед отобрать
     * @param array $mass Массив, в котором будем производить выборку
     * @return array
     */
    public static function ClearArray($items, $mass)
    {
        foreach ($mass as $key => $value)
        {
            if (array_search($key, $items) === false)
                unset($mass[$key]);
        }
        return $mass;
    }

    /**
     * Создать простой ассоциативный массив вида ключ->значение из сложного массива или объекта
     * @param array_or_object $mass Массив или объект
     * @param string $name Свойство в ассоциативном массиве для ключа
     * @return array
     */
    public static function Make1Array($mass, $name = '')
    {
        $newarray = array();
        foreach ($mass as $item)
        {
            if (empty($name))
                array_push($newarray, (string) $item);
            else
            if (is_object($item))
                array_push($newarray, $item->$name);
            elseif (is_array($item))
                array_push($newarray, $item[$name]);
        }
        return $newarray;
    }

    /**
     * Создать простой ассоциативный массив вида ключ->значение из сложного массива или объекта
     * @param array_or_object $mass Массив или объект
     * @param string $name Свойство в ассоциативном массиве для ключа
     * @param string $value Свойство в ассоциативном массиве для значения
     * @return array
     */
    public static function Make2Array($mass, $name, $value)
    {
        $newarray = array();
        foreach ($mass as $item)
        {
            if (is_object($item))
            {
                // Если для значений был передан массив, обрабатываем его особым образом
                // Если в значениях массива встретится массив, значит это функция..
                // Если в значениях встретится простая строка, то передаем её как строку
                if (is_array($value))
                {
                    $item_value = '';
                    foreach ($value as $value_param)
                    {
                        if (is_array($value_param))
                        {
                            $item_method = $value_param[0];
                            if (isset($value_param[1]))
                                $item_value .= $item->$item_method($value_param[1]);
                            else
                                $item_value .= $item->$item_method();
                        } else
                            $item_value .= $value_param;
                    }
                    $newarray[$item->$name] = $item_value;
                }
                elseif (empty($value))
                    $newarray[$item->$name] = (string) $item;
                else
                    $newarray[$item->$name] = method_exists($item, $value) ? $item->$value(): $item->$value;
            }
            if (is_array($item) && !is_array($value))
                $newarray[$item[$name]] = $item[$value];
        }
        return $newarray;
    }

	/**
	 * Retrieve a single key from an array. If the key does empty in the
	 * array, the default value will be returned instead.
	 *
	 * @param   array   array to extract from
	 * @param   string  key name
	 * @param   mixed   default value
	 * @return  mixed
	 */
	public static function get_empty($array, $key, $default = NULL)
	{
		return !empty($array[$key]) ? $array[$key] : $default;
	}


}