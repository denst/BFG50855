<?

class Controller_Regions extends Main {

    public $template = 'admin';
    protected $document_template = 'admin';

    protected $_model_name = 'region';
    protected $_add = array(
        'name' => array(
            'tag' => 'input',
            'label' => 'Название региона',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'name_rp' => array(
            'tag' => 'input',
            'label' => 'Название - род. п.',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'name_pp' => array(
            'tag' => 'input',
            'label' => 'Название - пред. п.',
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
        'code' => array(
            'tag' => 'input',
            'label' => 'Код для домена',
            'attributes' => array('data-validation' => ''),
        ),
        'countries_id' => array(
            'tag' => 'hidden',
            'label' => '',
        ),
    );

    protected $_messages = array(
        'add' => array(
            'success' => 'Регион создан',
            'error' => 'Ошибка создания региона',
        ),
        'edit' => array(
            'success' => 'Регион отредактрован',
            'error' => 'Ошибка редактрования региона',
        ),
        'delete' => array(
            'success' => 'Регион удален',
            'error' => 'Ошибка удаления региона',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание нового региона',
                'button' => 'Создать',
            ),
            'edit' => array(
                'caption' => 'Редактирование региона "' . (string)$model->name . '"',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление региона "' . (string)$model->name . '"',
                'answer' => 'Вы в курсе, что вы удаляете регион "' . (string)$model->name . '"?',
                'button' => 'Удалить регион',
            ),
            'copytoclipboard' => 'true'
        );
    }

	function before() {
		parent::before();
		$this->checkAdminLogin();
	}

    function action_list() {
        $country = ORM::factory('country',$this->request->param('id'));

        if (!$country->loaded())
            throw new HTTP_Exception_404;

        $regions = ORM::factory('region')->where('countries_id','=',$country->id);
        

        $filter = Arr::get($_COOKIE,COOKIE_FILTER.'sort_regions_by');
        if (!empty($filter)) {
            switch ($filter) {
                case 'name':
                    $regions->order_by('name');
                break;
                case 'domain':
                    $regions->order_by('domain');
                break;
                case 'citycount':
                    $regions->order_by(db::expr('(SELECT count(*) FROM cities WHERE regions_id = region.id)'));
                break;
            }
        }

		$view = View::factory('pages/regions/list');
		$view->regions = $regions->pagination(50);
		$view->pager = $regions->pagination_html(50);
        $view->country = $country;

		$this->template->content = $view;
		$this->template->title = 'Управление регионами ' . $country->name_pp;
	}

	function action_add()
	{
        $this->controller_add(array(
            'countries_id' => $this->request->param('id')
        ));
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
