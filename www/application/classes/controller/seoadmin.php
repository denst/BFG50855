<?

class Controller_Seoadmin extends main {

    public $template = 'admin';
    protected $document_template = 'admin';

    //public $_add = array('fields' => array('url','name', 'title','keywords','desc','h1','main','footer'));

    protected $_model_name = 'seo';
    protected $_add = array(
        'name' => array(
            'tag' => 'input',
            'label' => 'Название',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'countries_id' => array(
            'tag' => 'select',
            'label' => 'Страна',
            'model' => 'country',
            'model_key' => 'id',
            'model_value' => 'name',
            'attributes' => array('data-validation' => 'notempty'),
        ),
        'url' => array(
            'tag' => 'input',
            'label' => 'URL',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'title' => array(
            'tag' => 'input',
            'label' => 'Заголовок (title)',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'keywords' => array(
            'tag' => 'input',
            'label' => 'Ключевые слова',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'h1' => array(
            'tag' => 'input',
            'label' => 'H1',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'desc' => array(
            'tag' => 'textarea',
            'label' => 'Описание',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'main' => array(
            'tag' => 'textarea',
            'label' => 'Основной текст',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
        'footer' => array(
            'tag' => 'textarea',
            'label' => 'Текст в подвале',
            'attributes' => array('data-validation' => 'notempty;'),
        ),
    );

    protected $_messages = array(
        'add' => array(
            'success' => 'Страница создана',
            'error' => 'Ошибка создания страницы',
        ),
        'edit' => array(
            'success' => 'Страница отредактрована',
            'error' => 'Ошибка редактрования страницы',
        ),
        'delete' => array(
            'success' => 'Страница удалена',
            'error' => 'Ошибка удаления страницы',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание новой страницы',
                'button' => 'Создать',
            ),
            'edit' => array(
                'caption' => 'Редактирование страницы "' . (string)$model->name . '"',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление страницы "' . (string)$model->name . '"',
                'answer' => 'Вы в курсе, что вы удаляете сраницу "' . (string)$model->name . '"?',
                'button' => 'Удалить',
            )
        );
    }


	function before()
	{
		parent::before();
		$this->checkAdminLogin();
	}

    function action_index() {
        $view = View::factory('pages/seo/list');
        $view->countries = ORM::factory('country')->find_all();
        foreach($view->countries as $country) {
            $pages = ORM::factory('seo')->where('countries_id','=',$country->id);
            $view->set('pages_'.$country->id,$pages->pagination(100));
            $view->set('pager_'.$country->id,$pages->pagination_html(100));
        }
        $this->template->content = $view;
        $this->template->title = 'Управление описаниями субдоменов';
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

