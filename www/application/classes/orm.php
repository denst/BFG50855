<?php

/**
 * Дополнительные методы класса ORM
 *
 * @author romka
 */
class ORM extends Kohana_ORM
{

    protected $specialchars = false;

    /**
     * Включает или выключает экранирование выводящих символов в ORM
     * @param boolean $mode Режим экранирования
     */
    public function specialChars($mode)
    {
        $this->specialchars = $mode;
    }


	/**
	 * Добавляет новую строку в базу
	 * @param array $values Ассоциативный массив значений, которые надо будет сохранить
	 * @return bool
	 */
	public function add_item($values)
	{
		try
		{
			$values = Arr::ClearArray($this->_add['fields'], $values);

			// Получим массив всех зависимостей многие-многие, чтобы потом добавлять в них элементы
			$has_many_throughs = $this->get_throughs('add');

			$add_array = array();
			foreach($values as $column => $value)
			{
				if ($value != 'null')
				{
					if (in_array($column,$has_many_throughs) && !empty($value))
						array_push($add_array,array($column,$value));
					else
						$this->$column = $value;
				}
			}

			if (!empty($add_array))
			{
				$db = Database::instance();
				$db->begin();
			}

			// Обработаем все булевые колонки
			foreach($this->_add['fields'] as $field)
			{
				if (isset($this->_table_columns[$field])
						&& $this->_table_columns[$field]['type'] === 'int'
						&& $this->_table_columns[$field]['min'] === '-128'
						&& $this->_table_columns[$field]['max'] === '127')
					if (isset($values[$field]) && $values[$field] == 'null')
						$this->$field = null;
					elseif (isset($values[$field]))
						$this->$field = 1;
                    else
						$this->$field = 0;
			}

			$extra_validation = $this->GetRules('add');
			$this->save($extra_validation);

			// После сохранения заполним объект зависимостями многие-многие, если они были
			if (!empty($add_array))
			{
				foreach($add_array as $add)
					$this->add($add[0],$add[1]);
			}

			if (!empty($add_array))
			{
				$db->commit();
			}

			return true;
		}
		catch (ORM_Validation_Exception $e)
		{
			return false;
		}
	}

	/**
	 * Изменяет строку в базе
	 * @param array $values Ассоциативный массив значений, которые надо будет сохранить
	 * @return bool
	 */
	public function edit_item($values)
	{
		try
		{
			if (isset($this->_edit)) $fields = $this->_edit;
				else $fields = $this->_add;

                        if(isset($values['desc']))
                            $values['desc'] = str_replace ('&nbsp;', ' ', $values['desc']);
			$values = Arr::ClearArray($fields['fields'], $values);

			// Получим массив всех зависимостей многие-многие, чтобы потом добавлять в них элементы
			$has_many_throughs = $this->get_throughs('edit');

			$add_array = array();
			foreach($values as $column => $value)
			{
				if ($value != 'null')
				{
					if (in_array($column,$has_many_throughs) && !empty($value))
						array_push($add_array,array($column,$value));
					else
						$this->$column = $value;
				}
			}

			// Обработаем все булевые колонки
			foreach($fields['fields'] as $field)
			{
				if (isset($this->_table_columns[$field])
						&& $this->_table_columns[$field]['type'] === 'int'
						&& $this->_table_columns[$field]['min'] === '-128'
						&& $this->_table_columns[$field]['max'] === '127')
					if (isset($values[$field]) && $values[$field] == 'null')
						$this->$field = null;
					elseif (isset($values[$field]))
						$this->$field = 1;
                    else
						$this->$field = 0;
			}

            // Если есть связи "многие-ко-многим", запускаем транзакцию
			if (!empty($has_many_throughs))
			{
				$db = Database::instance();
				$db->begin();
			}

			$extra = $this->GetRules('edit');
			$this->save($extra);

			// Удалим все связи многие-многие, которые были до этого
			foreach($has_many_throughs as $through)
			{
				DB::delete($this->_has_many[$through]['through'])
					->where($this->_has_many[$through]['foreign_key'],'=',$this->id)
					->execute();
			}

			// Заполним эти связи новыми данными
			if (!empty($add_array))
			{
				foreach($add_array as $add)
					$this->add($add[0],$add[1]);
			}

			if (!empty($has_many_throughs))
			{
				$db->commit();
			}

			return true;
		}
		catch (ORM_Validation_Exception $e)
		{
			return false;
		}
	}

	/**
	 * Возаращает название таблицы модели
	 * @return string
	 */
	public function get_table_name()
	{
		return $this->_table_name;
	}

	/**
	 * Получить количество записей по параметрам
	 * @param string $field Поле, по которому считать
	 * @param string $value Значение поля, по которому считать
	 * @param int $id ID записи, которая не будет учитываться
	 * @param bool $is_orm Для orm ли используется запрос
	 * @return int
	 */
	public function get_count($field = null, $value = null, $id = null, $is_orm = true)
	{
		$query = DB::select(array(DB::expr('COUNT(*)'), 'total'))->from($this->_table_name);

		if (!empty($field) AND !empty($value))
		{
			$query->where($field, '=', $value);

			if (!is_null($id) && is_numeric($id))
			{
				$query->and_where('id', '!=', $id);
			}
		}
		else
		{
			if (!is_null($id) && is_numeric($id))
			{
				$query->where('id', '!=', $id);
			}
		}

		$result = $query->execute()->get('total');

		if ($is_orm)
		{
			if ($result > 0)
				return FALSE;
			else
				return TRUE;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Узнать, есть ли в таблице запись с указанным айдишником
	 * @param string $table Таблица, в которой искать запись
	 * @param string $id Айдишник записив таблице
	 * @return bool
	 */
	public static function static_exists($table, $id)
	{
		if (empty($id)) return FALSE;

		if (is_array($id))
		{
			// Если это массив - смотрим наличие всех элементов массива в таблице. Если все элементы на месте - возвращаем TRUE
			$ids_count = count($id);

			$query = DB::select(array(DB::expr('COUNT(id)'), 'total'))->from($table)
					->where('id', 'in',DB::expr('('.implode(',',$id).')'));

			$result_count = (int)$query->execute()->get('total');

			if ($result_count === $ids_count)
				return TRUE;
			else
				return FALSE;
		} else {
			$query = DB::select(array(DB::expr('COUNT(id)'), 'total'))->from($table)
					->where('id', '=', $id);

			$result = $query->execute()->get('total');

			if ($result > 0)
				return TRUE;
			else
				return FALSE;
		}
	}

	protected function GetRules($operation)
	{
		return null;
	}

	/**
	 * Обработать пагинацию
	 * @param int $items_per_page Количество элементов на страницу
	 * @return Database_result
	 */
	public function pagination($items_per_page = 20)
	{
		if (Kohana::$profiling === TRUE)
			$benchmark = Profiler::start(PR_NORMAL, 'Получение данных с пейджингом');

		$page = Arr::get($_GET,'page',1);
		$pages = $this->get_all_count_pages($items_per_page);

		if ($page < 1) $page = 1;
		if ($page > $pages) $page = $pages;

		$model = clone $this;
		$result = $model->limit($items_per_page)->offset(($page-1)*$items_per_page)->find_all();

		if (isset($benchmark))
			Profiler::stop($benchmark);

		return $result;
	}

    /**
     * Возвращает информацию о текущей странице и общем количестве страниц
     * @param int $items_per_page Количество элементов на странице
     * @return array Ассоциативный массив с двумя параметрами: page (текущая страница), pages (всего страниц)
     */
    public function pagination_info($items_per_page = 20)
    {
		$page = Arr::get($_GET,'page',1);
		if ($page < 1) $page = 1;

		$pages = $this->get_all_count_pages($items_per_page);

		return array(
            'page' => $page,
            'pages' => $pages,
        );
    }

	/**
	 * Сгенерировать вид
	 * @param int $items_per_page Количество объектов на странице
	 * @param string $uri Ссылка на страницу, по умолчанию ссылка на текущий контроллер
	 * @return string
	 */
	function pagination_html($items_per_page = 20,$uri = null)
	{
		if (Kohana::$profiling === TRUE)
			$benchmark = Profiler::start(PR_NORMAL, 'Генерация формы пейджинга');

		if (empty($uri)) $uri = Request::current()->uri();

		$page = Arr::get($_GET,'page',1);
		if ($page < 1) $page = 1;

		$pages = $this->get_all_count_pages($items_per_page);

		if ($page < 1) $page = 1;
		if ($page > $pages) $page = $pages;

		$prev_page = $page-1;
		if ($page == 1) $prev_page = 1;
		$next_page = $page+1;
		if ($page == $pages) $next_page = $pages;

		$params = $_GET;
		if (isset($params['page'])) unset($params['page']);

		// Сгенерируем правильную ссылку на страницы пагинации
		$link = '';
		$x = 1;
		foreach($params as $key => $value)
		{
			if ($x === 1)
				$link .= '?'.$key.'='.$value;
			else
				$link .= '&'.$key.'='.$value;
			$x = $x + 1;
		}
		if (empty($link))
			$link = '?';
		else
			$link .= '&';

		if ($pages > 1)
		{
			$html  = '<ul id="pages" class="mt10px mb10px">';

            if ($page != 1)
            {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page=1"><img src="/themes/images/admin/pag2l.png"></a></li>';
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.$prev_page.'"><img src="/themes/images/admin/pag1l.png"></a></li>';
            }

            if ($page > 3) {
                $html .= '<li><a rel="nofollow" class="btn"\ href="/'.$uri.$link.'page='.($page-3).'">'.($page-3).'</a></li>';
            }
            if ($page > 2) {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.($page-2).'">'.($page-2).'</a></li>';
            }
            if ($page > 1) {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.($page-1).'">'.($page-1).'</a></li>';
            }

            $html .= '<li><a rel="nofollow" class="btn btn-info" href="/'.$uri.$link.'page='.$page.'">'.$page.'</a></li>';

            if (($pages - $page ) > 1) {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.($page+1).'">'.($page+1).'</a></li>';
            }
            if (($pages - $page ) > 2) {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.($page+2).'">'.($page+2).'</a></li>';
            }
            if (($pages - $page ) > 3) {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.($page+3).'">'.($page+3).'</a></li>';
            }


            if ($page != $pages)
            {
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.$next_page.'"><img src="/themes/images/admin/pag1r.png"></a></li>';
                $html .= '<li><a rel="nofollow" class="btn" href="/'.$uri.$link.'page='.$pages.'"><img src="/themes/images/admin/pag2r.png"></a></li>';
            }

			$html .= '</ul> <div class="clear"></div>';

			$result = $html;
		} else {
			$result = '';
		}

		if (isset($benchmark))
			Profiler::stop($benchmark);

		return $result;
	}

	/**
	 * Функция высчитывает общее количество страниц у данной модели.
	 * @param int $items_per_page Количество элементов на странице
	 * @return int
	 */
	private function get_all_count_pages($items_per_page)
	{
		$count = $this->get_model_count();
		$pages = ceil($count / $items_per_page);
		if ($pages < 1) $pages = 1;
		return $pages;
	}

	/**
	 * Получить количество записей, которые вернет модель с текущими параметрами
     * @return INT
	 */
	public function get_model_count()
	{
		$count = null;

        $model = clone $this;
        $model->_build(DATABASE::SELECT);
        $model->_db_builder->from(array($this->_table_name,$this->_object_name));

        $from_pos = mb_strpos(mb_strtolower($model->_db_builder),'from');
        if($from_pos !== false)
        {
            $query = 'SELECT count(*) as count ' . mb_substr($model->_db_builder,$from_pos);
            $count = DB::query(DATABASE::SELECT, $query)->execute()->get('count');
        } else {
            // Если не получилось видоизменить SQL запрос, используем бородатый вариант
            // Старый способ основан на работе напрямую с ORM
            $model = clone $this;
            $count = $model->find_all()->count();
        }

		return $count;
	}

	/**
	 * Получить массив всех зависимостей многие-многие у модели
	 */
	private function get_throughs($mode)
	{
		if (!in_array($mode,array('edit','add'))) throw new Kohana_Exception('неизвестный режим');

		if ($mode === 'edit' && isset($this->_edit) && isset($this->_edit['fields']))
			$fields = $this->_edit['fields'];
		if (($mode === 'add' || !isset($fields)) && isset($this->_add) && isset($this->_add['fields']))
			$fields = $this->_add['fields'];

		$has_many_throughs = array();
		if (isset($this->_has_many))
		foreach($this->_has_many as $name => $relation)
		{
			if (isset($relation['through']) && in_array($name,$fields))
				array_push($has_many_throughs,$name);
		}
		return $has_many_throughs;
	}


	public function list_columns()
	{
		$cache_lifetime = 360000; // 100 часов
		$cache_key = $this->_table_name . "structure";
		if ($result = Kohana::cache($cache_key, NULL, $cache_lifetime))
		{
			$_columns_data = $result;
		}

		if (!isset($_columns_data))
		{
			$_columns_data = $this->_db->list_columns($this->_table_name);
			Kohana::cache($cache_key, $_columns_data, $cache_lifetime);
		}

		return $_columns_data;
	}

    // Замена оригинального магического геттера, с помощью которой мы экранируем весь вывод для защиты приложения от вставки скриптов
    public function __get($column)
    {
        $column = parent::__get($column);

        if (is_string($column) && $this->specialchars)
            return HTML::chars($column);
        else
            return $column;
    }

}

