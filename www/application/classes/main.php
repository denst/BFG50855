<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Main extends Template
{

	function up_gorder($id)
	{
		$model = ORM::factory($this->_model_name, $id);
		if(!$model->loaded())	return false;
		$params = $this->get_params($model);
		$gorder = new gorder($model->get_table_name(), $params['gorder']['order_group_field']);
		$gorder->set_group($model->$params['gorder']['order_group_field']);
		if ($gorder->up($model->id))
        {
            $this->SendJSONData(JSONA_REFRESHPAGE);
            return true;
        } else {
            $this->SendJSONData(JSONA_ERROR,'Невозможно поднять запись');
            return false;
        }
	}

	function down_gorder($id)
	{
		$model = ORM::factory($this->_model_name, $id);
		if(!$model->loaded())	return false;
		$params = $this->get_params($model);
		$gorder = new gorder($model->get_table_name(), $params['gorder']['order_group_field']);
		$gorder->set_group($model->$params['gorder']['order_group_field']);
		if ($gorder->down($model->id))
        {
            $this->SendJSONData(JSONA_REFRESHPAGE);
            return true;
        } else {
            $this->SendJSONData(JSONA_ERROR,'Невозможно опустить запись');
            return false;
        }
	}

	function up_order($id)
	{
		$model = ORM::factory($this->_model_name, $id);
		if(!$model->loaded())	return false;
		$order = new order($model->get_table_name());
        if ($order->up($model->id))
        {
            $this->SendJSONData(JSONA_REFRESHPAGE);
            return true;
        } else {
            $this->SendJSONData(JSONA_ERROR,'Невозможно поднять запись');
            return false;
        }
	}

	function down_order($id)
	{
		$model = ORM::factory($this->_model_name, $id);
		if(!$model->loaded()) return false;
		$order = new order($model->get_table_name());

        if ($order->down($model->id))
        {
            $this->SendJSONData(JSONA_REFRESHPAGE);
            return true;
        } else {
            $this->SendJSONData(JSONA_ERROR,'Невозможно опустить запись');
            return false;
        }
	}

	function add($values = null)
	{
		if ($values == null)
			$values = $_POST;

		if (Security::check_token())
		{
			/* @var $model interface_model */
			$model = ORM::factory($this->_model_name);

			if ($model->add_item($values))
			{
                $this->SendJSONData(array(JSONA_REFRESHPAGE => '', JSONA_COMPLETED => $this->_messages['add']['success']));
				return $model;
			}
			else
			{
                $this->SendJSONData(JSONA_ERROR, $this->_messages['add']['error']);
				return false;
			}
		} else {
            $this->SendJSONData(JSONA_ERROR, 'Ошибка валидации сообщения');
			return false;
		}
	}

	function add_order($values = null)
	{
		if ($values == null)
			$values = $_POST;
		$order = new order(ORM::factory($this->_model_name)->get_table_name());
		$values['order'] = $order->get_insert_order();

		$this->add($values);
	}

	function add_gorder($values = null)
	{
		if ($values == null)
			$values = $_POST;
		$model = ORM::factory($this->_model_name);
		$params = $this->get_params($model);
		$gorder = new gorder($model->get_table_name(), $params['gorder']['order_group_field']);
		$gorder->set_group($values[$params['gorder']['order_group_field']]);
		$values['order'] = $gorder->get_insert_order();
		return $this->add($values);
	}

	function edit($id, $values = null)
	{
		if (Security::check_token())
		{
			$model = ORM::factory($this->_model_name,$id);
			if (!$model->loaded()) throw new HTTP_Exception_404('model '.$this->_model_name.' not loaded');

			if ($values == null)
				$values = $_POST;

			if ($model->edit_item($values))
			{
                $this->SendJSONData(array(JSONA_REFRESHPAGE => '', JSONA_COMPLETED => $this->_messages['edit']['success']));
				return true;
			}
			else
			{
                $this->SendJSONData(JSONA_ERROR, $this->_messages['edit']['error']);
				return false;
			}
		} else {
            $this->SendJSONData(JSONA_ERROR, 'Ошибка валидации сообщения');
			return false;
		}
	}

    //TODO написать этот контроллер. В данный момент этот код скопирован у метода edit_gorder и немного вначале исправлен. Понять, что тут и как и написать рабучий вариант.
//	function edit_order($id, $values = null)
//	{
//		if ($values == null)
//			$values = $_POST;
//
//		$model = ORM::factory($this->_model_name, $id);
//		if(!$model->loaded())	return false;
//		$params = $this->get_params($model);	// получаем параметры, нам нужен один в котором указано поле для группы
//		$gorder = new order($model->get_table_name(), $params['order']['order_group_field']);	// Создаём экземпляр класса сортировки, отдаём название таблицы и поле где указаны группы
//
//		// Категория изменилась надо сохранить старые значения
//		$old_order = $model->order;
//		$old_cat = $model->$params['order']['order_group_field'];
//
//		// Проверим изменилась ли категория у записи
//		if($values[$params['order']['order_group_field']] != $old_cat)
//		{
//			$gorder->set_group($values[$params['order']['order_group_field']]);	//Указываем новую группу сортировки
//			$values['order'] = $gorder->get_insert_order ();
//		}
//
//
//		if (!$this->edit($id, $values)) return false;
//		// Дальнейший код исполнится, только если редактирование прошло успешно
//
//		// Проверим изменилась ли категория у записи
//		if($values[$params['gorder']['order_group_field']] != $old_cat)
//		{
//			//Вернёмся к старой группе
//			$gorder->set_group($old_cat);
//			$gorder->update_after_del($old_order);	// сдвинем все записи чтобы закрыть пустое место
//		}
//		return true;
//	}

	function edit_gorder($id, $values = null)
	{
		if ($values == null)
			$values = $_POST;

		$model = ORM::factory($this->_model_name, $id);
		if(!$model->loaded())	return false;
		$params = $this->get_params($model);	// получаем параметры, нам нужен один в котором указано поле для группы
		$gorder = new gorder($model->get_table_name(), $params['gorder']['order_group_field']);	//Создаём экземпляр класса сортировки, отдаём название таблицы и поле где указаны группы

		// Категория изменилась надо сохранить старые значения
		$old_order = $model->order;
		$old_cat = $model->$params['gorder']['order_group_field'];

		// Проверим изменилась ли категория у записи
		if($values[$params['gorder']['order_group_field']] != $old_cat)
		{
			$gorder->set_group($values[$params['gorder']['order_group_field']]);	//Указываем новую группу сортировки
			$values['order'] = $gorder->get_insert_order ();
		}


		if (!$this->edit($id, $values)) return false;
		// Дальнейший код исполнится, только если редактирование прошло успешно

		// Проверим изменилась ли категория у записи
		if($values[$params['gorder']['order_group_field']] != $old_cat)
		{
			//Вернёмся к старой группе
			$gorder->set_group($old_cat);
			$gorder->update_after_del($old_order);	// сдвинем все записи чтобы закрыть пустое место
		}
		return true;
	}

	function delete($id)
	{
		if (Security::check_token())
		{
			$model = ORM::factory($this->_model_name,$id);
			if (!$model->loaded()) return false;

			if (isset($_POST['confirm']))
			{
				try
				{
					$model->delete();
                                    $this->SendJSONData(array(
                                        JSONA_COMPLETED => 'Данные удалены',
                                        JSONA_REFRESHPAGE => ''
                                    ));
				}
				catch (ORM_Validation_Exception $e)
				{
                    $this->SendJSONData(JSONA_ERROR,'Невозможно удалить запись');
					return false;
				}
			}
		} else {
            $this->SendJSONData(JSONA_ERROR, 'Ошибка валидации сообщения');
			return false;
		}
	}

	function delete_order($id)
	{
		$model = ORM::factory($this->_model_name,$id);
		if(!$model->loaded())	return false;
		$old_order = $model->order;
		if($this->delete($id))
		{
			$order = new order(ORM::factory($this->_model_name)->get_table_name());
			/* $order order*/
			$order->update_after_del($old_order);
			return true;
		}
		return false;
	}

	function delete_gorder($id)
	{
		$model = ORM::factory($this->_model_name, $id);
		if(!$model->loaded())	return false;
		$params = $this->get_params($model);
		$gorder = new gorder($model->get_table_name(), $params['gorder']['order_group_field']);
		$gorder->set_group($model->$params['gorder']['order_group_field']);
		$old_order = $model->order;
		if($this->delete($id))
		{
			$gorder->update_after_del($old_order);
			return true;
		}
		return false;
	}

	function form_add($values = null)
	{
		$model = ORM::factory($this->_model_name);

		$fields = $this->_add;
		if (count($model->_add['fields']) != count($fields))
			die('количество колонок добавления в модели не совпадает с количеством колонок в контроллере');

		$_values = array();
		foreach($fields as $column => $field)
		{
			$_values[$column] = null;
			if (isset($values[$column])) $_values[$column] = $values[$column];
		}
		$view = View::factory('global/form_add');
		$view->global_fields =$fields;
		$view->global_params = $this->get_params($model);
		$view->global_values = $_values;
		return $view;
	}

	function form_edit($id)
	{
		$model = ORM::factory($this->_model_name,$id);

		if (!$model->loaded()) throw new HTTP_Exception_404('model '.$this->_model_name.' not loaded');

		if (isset($this->_edit)) $fields = $this->_edit;
			else $fields = $this->_add;

		if (isset($model->_edit)) $model_fields = $model->_edit;
			else $model_fields = $model->_add;

		if (count($model_fields['fields']) != count($fields))
			throw new HTTP_Exception_404('количество колонок изменения в модели не совпадает с количеством колонок в контроллере');

		$view = View::factory('global/form_edit');
		$view->global_fields = $fields;
		$view->global_params = $this->get_params($model);
		$view->global_item  = $model;
		return $view;
	}

	function form_delete($id)
	{
		$model = ORM::factory($this->_model_name,$id);

		if (!$model->loaded()) throw new HTTP_Exception_404('model '.$this->_model_name.' not loaded');

		$view_items = array(
			'global_params' => $this->get_params($model),
			'global_item'   => $model,
		);

		return View::factory('global/form_delete', $view_items);
	}

	public function controller_add($values = null)
	{
		$rez = FALSE;
		if ($this->isJson())
		{
			$status = $this->add();
    		return $status;
		}

		$this->template->content = $this->form_add($values);

        return $rez;
	}

	public function controller_add_order($values = null)
	{
		if ($this->isJson())
		{
			$status = $this->add_order();
            return $status;
		} else {
    		$this->template->content = $this->form_add($values);
        }
	}

	public function controller_add_gorder($values = null)
	{
		if ($this->isJson())
		{
			$status = $this->add_gorder();
			return $status;
		} else {
    		$this->template->content = $this->form_add($values);
        }
	}

	public function controller_edit()
	{
		$id = $this->request->param('id');

		if ($this->isJson())
		{
			$status = $this->edit($id);
            return $status;
		} else {
    		$this->template->content = $this->form_edit($id);
        }

        return FALSE;
	}

	public function controller_edit_gorder()
	{
		$id = $this->request->param('id');

		if ($this->isJson())
		{
			$status = $this->edit_gorder($id);
            return $status;
		}

		$this->template->content = $this->form_edit($id);
	}

	public function controller_delete()
	{
		$id = $this->request->param('id');

		if ($this->isJson())
		{
			if (isset($_POST['cancel']))
				$this->SendJSONData(JSONA_REFRESHPAGE);
			else {
				$status = $this->delete($id);
                return $status;
			}
		} else {
			$this->template->content = $this->form_delete($id);
		}
	}

	public function controller_delete_order()
	{
		$id = $this->request->param('id');

		if ($this->isJson())
		{
			$status = $this->delete_order($id);
            return $status;
		}

		$this->template->content = $this->form_delete($id);
	}

	public function controller_delete_gorder()
	{
		$id = $this->request->param('id');

		if ($this->isJson())
		{
			if (isset($_POST['cancel']))
				$this->SendJSONData(JSONA_REFRESHPAGE);
			else {
				$status = $this->delete_gorder($id);
                return $status;
			}
		} else {
			$this->template->content = $this->form_delete($id);
		}
	}

	public function controller_up_order()
	{
		$id = $this->request->param('id');
		if(is_numeric($id))
		{
            $status = $this->up_order($id);
            return $status;
		}
	}

	public function controller_down_order()
	{
		$id = $this->request->param('id');
		if (is_numeric($id))
		{
			$status = $this->down_order($id);
            return $status;
		}
	}

	public function controller_up_gorder()
	{
		$id = $this->request->param('id');
		if(is_numeric($id))
		{
			$status = $this->up_gorder($id);
            return $status;
		}
	}

	public function controller_down_gorder()
	{
		$id = $this->request->param('id');
		if(is_numeric($id))
		{
			$status = $this->down_gorder($id);
            return $status;
		}
	}

}
