<?
	/* @var $global_fields array  */
	/* @var $message array  */
?>

<div id="edit-form">

    <?

    echo '<h2>'.$global_params['edit']['caption'].'</h2>';

    if (isset($message)) echo '<h3>'.$message.'</h3>';

    // Сначала выведем все скрытые инпуты
    foreach ($global_fields as $name => $field)
    {
        if ($field['tag'] == 'hidden') {
            echo Form::hidden($name,$global_item->$name,Arr::get($field,'attributes'));
        }
    }

    echo '<table id="redakt" border="1" cellspacing="0" cellpadding="0" bordercolor="#ccc">';

    $javascript = '';

    foreach ($global_fields as $name => $field)
    {
        if (!isset($field['attributes'])) $field['attributes'] = null;

        if ($field['tag'] != 'hidden')
        {
            echo '<tr><td class="td7">' . Arr::get($field,'label') . '</td><td>';

            switch ($field['tag'])
            {
                case 'input':
                    echo Form::input($name,$global_item->$name,$field['attributes']);
                break;
                case 'checkbox':
                    echo Form::checkbox($name,1,(bool)$global_item->$name,$field['attributes']);
                break;
                case 'select':
                    if (isset($field['options']))
                    {
                        $options = $field['options'];
                    } elseif (isset($field['model'])) {
                        $model = ORM::factory($field['model']);

                        if (!empty($field['model_wheres'])) foreach($field['model_wheres'] as $key => $where)
                        {
                            switch ($where[2])
                            {
                                case '%current_user_friends%':
                                    // Создадим массив из друзей текущего пользователя
                                    $where[2] = Arr::Make1Array($user->friends->find_all(),'id');
                                    if (empty($where[2]))
                                        $where[2] = DB::expr('(0)');
                                    else
                                        $where[2] = DB::expr('('.implode(',',$where[2]).')');
                                    break;
                                case '%current_user%':
                                    $where[2] = $user->id;
                                    break;
                                default:
                                    break;
                            }

                            if ($where[2] == '%current_user%') $where[2] = $user->id;

                            if ($key == 0)
                                $model->where($where[0],$where[1],$where[2]);
                            else
                                $model->and_where($where[0],$where[1],$where[2]);
                        }

                        if (!empty($field['model_orders'])) $model->order_by($field['model_orders']);

                        $options = Arr::Make2Array($model->find_all(), $field['model_key'], $field['model_value']);
                    }

                    $javascript .= "$('select[name=\"$name\"]').chosen();";
                    $field['attributes']['data-placeholder'] = $field['label'].'..';
                    if (empty($options)) $options = array(''=>'список пуст');
                    echo Form::select($name,$options,$global_item->$name,$field['attributes']);
                break;
                case 'select-multi':
                    if (isset($field['options']))
                    {
                        $options = $field['options'];
                    } elseif (isset($field['model'])) {
                        $model = ORM::factory($field['model']);
                        if (!empty($field['model_orders'])) $model->order_by($field['model_orders']);

                        $options = Arr::Make2Array($model->find_all(),
                            $field['model_key'], $field['model_value']);

                        $selected = Arr::Make1Array($global_item->$name->find_all(),$field['model_key']);
                    }

                    $javascript .= "$('select[name=\"$name\"]').chosen();";
                    $field['attributes']['data-placeholder'] = $field['label'].'..';
                    $field['attributes']['multiple'] = '.';
                    if (empty($options)) $options = array(''=>'список пуст');
                    echo Form::select($name,$options,$selected,$field['attributes']);
                break;
                case 'search':

                    $value = ORM::factory($field['model'],array($field['model_key']=>$global_item->$name))->$field['model_value'];
                    echo Form::search($name, $field, 'начните набирать название..',array($global_item->$name => $value),false);

                break;
                case 'search-multi':

                    $values = $global_item->$name->find_all()->as_array();
                    echo Form::search($name, $field, 'начните набирать название..',$values,true);

                break;
                case 'textarea':
                    //$javascript .= "delete CKEDITOR.instances.{$name}; $('textarea[name={$name}]').ckeditor();";
                    //$javascript .= "Core.editor('textarea[name={$name}]');";

                    if (!isset($field['attributes']['class']))
                        $field['attributes']['class'] = 'ckeditor';
                    else
                        $field['attributes']['class'] = $field['attributes']['class'] . ' ckeditor';

                    $javascript .= "if (CKEDITOR.instances['$name']) { delete CKEDITOR.instances['$name']; } CKEDITOR.replace( '$name' );";

                    echo Form::textarea($name,$global_item->$name,$field['attributes']);
                break;
                default:
                break;
            }

            echo Form::validation($name);
            echo '</td></tr>';
        }
    }

    echo '</table>';

    // Выполним яваскрипт, если он найдется
    if (!empty($javascript)) {
        echo '<script type="text/javascript">$(document).ready(function() { '.$javascript.' });</script>';
    }

    ?>

</div>

<div class="senddata-token btn btn-primary mt15px"
        data-link="/<?=Request::current()->uri()?>"
        data-input="#edit-form"><?=$global_params['edit']['button']?>
</div>

<? if (isset($global_params['copytoclipboard'])) { ?>

    <div id="copydomain" class="btn btn-primary mt15px ml10px">Копировать домен</div>
    <div onclick="$('img.setactive',$('#items-list tr.active').next()).click()" class="btn btn-primary mt15px ml10px">Далее</div>

    <script type="text/javascript">
        var clip = new ZeroClipboard.Client();
        clip.setText('<?=$global_item->domain?>.realtynova.ru');
        clip.glue('copydomain');
    </script>

<? } ?>

<div class="clear"></div>