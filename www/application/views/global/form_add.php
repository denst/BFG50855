<?
	/* @var $global_fields array  */
	/* @var $message array  */
?>

<div id="create-form">

    <?

	echo '<h2>'.$global_params['add']['caption'].'</h2>';

	if (isset($message)) echo '<h3>'.$message.'</h3>';

    // Сначала выведем все скрытые инпуты
    foreach ($global_fields as $name => $field)
    {
        if ($field['tag'] == 'hidden') {
            echo Form::hidden($name,$global_values[$name],Arr::get($field,'attributes'));
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
                    echo Form::input($name,$global_values[$name],$field['attributes']);
                break;
                case 'checkbox':
                    echo Form::checkbox($name,1,(bool)$global_values[$name],$field['attributes']);
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

                            $model->and_where($where[0],$where[1],$where[2]);
                        }

                        if (!empty($field['model_orders'])) $model->order_by($field['model_orders']);

                        $options = Arr::Make2Array($model->find_all(), $field['model_key'], $field['model_value']);
                    }
                    $javascript .= "$('select[name=\"$name\"]').chosen();";
                    $field['attributes']['data-placeholder'] = $field['label'].'..';
                    if (empty($options)) $options = array(''=>'список пуст');
                    echo Form::select($name,$options,$global_values[$name],$field['attributes']);
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
                    }

                    $javascript .= "$('select[name=\"$name\"]').chosen();";
                    $field['attributes']['data-placeholder'] = $field['label'].'..';
                    $field['attributes']['multiple'] = '.';
                    if (empty($options)) $options = array(''=>'список пуст');
                    echo Form::select($name,$options,null,$field['attributes']);
                break;
                case 'search':

                    if (!empty($global_values[$name]))
                    {
                        $value = array(
                            $global_values[$name] =>
                            ORM::factory($field['model'],array($field['model_key']=>$global_item->$name))->$field['model_value']
                        );
                    } else {
                        $value = null;
                    }

                    echo Form::search($name, $field, 'начните набирать название..',$value,false);

                break;
                case 'search-multi':
                    echo Form::search($name, $field, 'начните набирать название..',null,true);

                break;
                case 'textarea':

                    //if (!isset($field['attributes']['class']))
                    //	$field['attributes']['class'] = 'ckeditor';
                    //else
                    //	$field['attributes']['class'] = $field['attributes']['class'] . ' ckeditor';
                    //$javascript .= "delete CKEDITOR.instances.{$name}; $('textarea[name={$name}]').ckeditor();";

                    if (!isset($field['attributes']['class']))
                        $field['attributes']['class'] = 'ckeditor';
                    else
                        $field['attributes']['class'] = $field['attributes']['class'] . ' ckeditor';

                    $javascript .= "if (CKEDITOR.instances['$name']) { delete CKEDITOR.instances['$name']; } CKEDITOR.replace( '$name' );";

                    echo Form::textarea($name,$global_values[$name],$field['attributes']);
                break;
                default:
                break;
            }

            echo Form::validation($name);
            echo '</td></tr>';

        }
	}

    echo '</table>';

    // Выполним яваскрипт, если он есть
    if (!empty($javascript)) {
        echo '<script type="text/javascript">$(document).ready(function() { '.$javascript.' });</script>';
    }

    ?>

</div>

<div class="senddata-token btn btn-primary mt15px"
        data-link="/<?=Request::current()->uri()?>"
        data-input="#create-form"><?=$global_params['add']['button']?>
</div>
<div class="clear"></div>
