<?

class Controller_Cities extends Main {

    public $template = 'admin';
    protected $document_template = 'admin';

    protected $_model_name = 'city';
    protected $_add = array(
        'name' => array(
            'tag' => 'input',
            'label' => 'Имя города',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'name_rp' => array(
            'tag' => 'input',
            'label' => 'Имя города - род. п.',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'name_pp' => array(
            'tag' => 'input',
            'label' => 'Имя города - пред. п.',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'domain' => array(
            'tag' => 'input',
            'label' => 'Домен',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'longitude' => array(
            'tag' => 'input',
            'label' => 'Широта',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'latitude' => array(
            'tag' => 'input',
            'label' => 'Долгота',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'regions_id' => array(
            'tag' => 'select',
            'label' => 'Регион',
            'model' => 'region',
            'model_key' => 'id',
            'model_value' => 'name',
            'attributes' => array('data-validation' => 'notempty'),
        ),
        'districts_id' => array(
            'tag' => 'select',
            'label' => 'Район',
            'model' => 'district',
            'model_key' => 'id',
            'model_value' => 'name',
            'attributes' => array('data-validation' => 'notempty'),
        ),
        'code' => array(
            'tag' => 'input',
            'label' => 'Код для домена',
            'attributes' => array('data-validation' => ''),
        ),
    );

    protected $_messages = array(
        'add' => array(
            'success' => 'Город создан',
            'error' => 'Ошибка создания города',
        ),
        'edit' => array(
            'success' => 'Город отредактрован',
            'error' => 'Ошибка редактрования города',
        ),
        'delete' => array(
            'success' => 'Город удален',
            'error' => 'Ошибка удаления города',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание нового города',
                'button' => 'Создать',
            ),
            'edit' => array(
                'caption' => 'Редактирование города "' . (string)$model->name . '"',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление города "' . (string)$model->name . '"',
                'answer' => 'Вы в курсе, что вы удаляете город "' . (string)$model->name . '"?',
                'button' => 'Удалить город',
            ),
            'copytoclipboard' => 'true'
        );
    }

	function before()
	{
		parent::before();
		$this->checkAdminLogin();
	}

	function action_list()
	{
        $region = ORM::factory('region',$this->request->param('id'));

        if (!$region->loaded())
            throw new HTTP_Exception_404;

        $cities = ORM::factory('city')->where('regions_id','=',$region->id);

        $filter = Arr::get($_COOKIE,COOKIE_FILTER.'sort_cities_by');
        if (!empty($filter)) {
            switch ($filter) {
                case 'name':
                    $cities->order_by('name');
                break;
                case 'domain':
                    $cities->order_by('domain');
                break;
            }
        }

		$view = View::factory('pages/cities/list');
    	$view->cities = $cities->pagination(50);
		$view->pager = $cities->pagination_html(50);
        $view->region = $region;

		$this->template->content = $view;
		$this->template->title = 'Управление городами ' . $region->name_pp;
	}

	function action_add()
	{
        $this->controller_add();
	}

	function action_edit()
	{
		$id = $this->request->param('id');

		if ($this->isJson())
		{
            if (Security::check_token())
            {
                $model = ORM::factory($this->_model_name,$id);
                if (!$model->loaded()) throw new HTTP_Exception_404('model '.$this->_model_name.' not loaded');

                if ($model->edit_item($_POST))
                {
                    $this->SendJSONData(array(JSONA_COMPLETED => $this->_messages['edit']['success']));
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
		} else {
    		$this->template->content = $this->form_edit($id);
        }
	}

	function action_delete()
	{
        $this->controller_delete();
	}

}

?>
on_delete()
	{
        $this->controller_delete();
	}

}

?>
