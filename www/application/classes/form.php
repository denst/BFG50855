<?php

defined('SYSPATH') or die('No direct script access.');

class Form extends Kohana_Form
{

    static function filter($title, $name, $items) {
        $name = 'filter_options_'.$name;
        $cookie = Arr::get($_COOKIE,$name);

        $selected = !empty($cookie) ? $cookie : null;
        $items = Arr::merge(array('' => $title), $items);

        return Form::select($name,$items,$selected,array(
            'onchange' => '$.cookie("'.$name.'",$(this).val()); Navigation.refreshPage();'
        ));
    }

    /**
     * Создает специальный лейбл с полем для вывода ошибки при непрохождении валидации
     */
    public static function validation($input)
    {
        return Form::label('error-' . $input, '', array('style' => 'display:none', 'id' => 'error-' . $input, 'class'=>'validation-error'));
    }

    /**
     * Возвращает инпут со сгенерированным ключом доступа.
     */
    public static function token()
    {
        return Form::hidden(TOKEN, Security::token(), array('id' => TOKEN));
    }

    /**
     * Возвращает специально сформированные инпуты для поиска
     * @param string $input Название инпута
     * @param string $alt Описание инпута
     * @param boolean $multiple Флаг, какой поиск у нас
     */
    public static function search($input, $model_params, $alt, $values = null, $multiple = false, $style = null) {
        if ($multiple === true) {
            $params = array(
                'style' => $style,
                'class' => 'search',
                'placeholder' => $alt,
                'data-search-type' => $model_params['model'],
                'data-input-name' => $input,
                'data-multiple' => '',
                'data-validation' => 'notempty',
                'onchange' => "Search.trySearch(this)",
                'onkeyup' => "Search.trySearch(this,event)",
            );

            $output = Form::input('', null, $params);

            $value_key = $model_params['model_key'];
            $value_name = $model_params['model_value'];

            $output .= '<div class="search-message-container" id="search_message_' . $input . '">';

            if (!empty($values))
                foreach ($values as $value)
                {
                    $output .= '
				<div>
					<img src="/themes/images/cross_16.png" style="float: left; margin-right: 5px; cursor: pointer;"
						onclick="$(this).parent().remove()" />
					<span>' . (empty($value_name) ? $value : $value->$value_name) . '</span>
					<input type="hidden" name="' . $input . '[]" value="' . $value->$value_key . '">
				</div>
				';
                }

            $output .= '</div>';
        } else {
            $params = array(
                'style' => $style,
                'class' => 'search',
                'placeholder' => $alt,
                'data-search-type' => $model_params['model'],
                'data-input-name' => $input,
                'onchange' => "Search.trySearch(this)",
                'onkeyup' => "Search.trySearch(this,event)",
            );
            $output = Form::input('', null, $params);

            if (empty($values))
            {
                $value_id = null;
                $value_name = null;
            }
            else
            {
                foreach ($values as $key => $value)
                {
                    $value_id = $key;
                    $value_name = $value;
                }
            }

            $output .= Form::hidden($input, $value_id, array('id' => 'hidden_input_' . $input));

            if (empty($value_name))
                $output .= '<div class="search-message-container" id="search_message_' . $input . '"></div>';
            else
                $output .= '<div class="search-message-container" id="search_message_' . $input . '">Выбрано: ' . $value_name . '</div>';
        }

        return '<div class="search-container">' . $output . '</div>';
    }

}
