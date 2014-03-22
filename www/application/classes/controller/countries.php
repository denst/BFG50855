<?

class Controller_Countries extends Main {

    public $template = 'admin';
    protected $document_template = 'admin';

    protected $_model_name = 'country';
    protected $_add = array(
        'name' => array(
            'tag' => 'input',
            'label' => 'Название страны',
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
        'code' => array(
            'tag' => 'input',
            'label' => 'Код для домена',
            'attributes' => array('data-validation' => ''),
        ),
        'price_types_id' => array(
            'tag' => 'select',
            'label' => 'Валюта',
            'model' => 'price',
            'model_key' => 'id',
            'model_value' => 'name',
            'attributes' => array('data-validation' => 'notempty'),
        ),
    );

    protected $_messages = array(
        'add' => array(
            'success' => 'Страна создана',
            'error' => 'Ошибка создания страны',
        ),
        'edit' => array(
            'success' => 'Страна отредактрована',
            'error' => 'Ошибка редактрования страны',
        ),
        'delete' => array(
            'success' => 'Страна удалена',
            'error' => 'Ошибка удаления страны',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание новой страны',
                'button' => 'Создать',
            ),
            'edit' => array(
                'caption' => 'Редактирование страны "' . (string)$model->name . '"',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление страны "' . (string)$model->name . '"',
                'answer' => 'Вы в курсе, что вы удаляете страну "' . (string)$model->name . '"?',
                'button' => 'Удалить страну',
            )
        );
    }

	function before()
	{
		parent::before();
		$this->checkAdminLogin();
	}

	function action_index()
	{
		$view = View::factory('pages/countries/list');
		$view->countries = ORM::factory('country')->pagination(50);
		$view->pager = ORM::factory('country')->pagination_html(50);

		$this->template->content = $view;
		$this->template->title = 'Управление странами';
	}

	function action_add()
	{
        $this->controller_add();
	}

	function action_edit()
	{
        $this->controller_edit();
	}

	function action_delete()
	{
        $this->controller_delete();
	}

}

?>
