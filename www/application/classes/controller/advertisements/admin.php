<?

class Controller_Advertisements_Admin extends main {

    public $template = 'admin';
    protected $document_template = 'admin';

    protected $_per_page = 50;
    protected $_model_name = 'advert';
    protected $_add = array(
        'title' => array(
            'tag' => 'input',
            'label' => 'Название',
            'attributes' => array(
                'data-validation' => 'notempty;'
            ),
        ),
        'desc' => array(
            'tag' => 'textarea',
            'label' => 'Описание',
            'attributes' => array(
                'data-validation' => 'notempty;'
            ),
        ),
        'alt' => array(
            'tag' => 'input',
            'label' => 'Название на латинском',
            'attributes' => array(
                'data-validation' => 'notempty;'
            ),
        ),
        'users_id' => array(
            'tag' => 'select',
            'label' => 'Владелец объявления',
            'model' => 'user',
            'model_key' => 'id',
            'model_value' => '',
            'attributes' => array(),
        ),
    );
    protected $_messages = array(
        'add' => array(
            'success' => 'Объявление создано',
            'error' => 'Ошибка создания объявления',
        ),
        'edit' => array(
            'success' => 'Объявление отредактировано',
            'error' => 'Ошибка редактирования объявления',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание нового объявления',
                'button' => 'Создать объявление',
            ),
            'edit' => array(
                'caption' => 'Редактирование объявления "' . $model->title . '"',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление объявления "' . $model->title . '"',
                'answer' => 'Вы в курсе, что вы удаляете объявление "' . $model->title . '"?',
                'button' => 'Удалить объявление',
            )
        );
    }

    function before() {
        parent::before();
        $this->checkAdminLogin();
    }

    function action_index() {
        $flats = ORM::factory('adverts_types_flat');

        $view = View::factory('pages/advertisements/admin');
        $view->flats = $flats->pagination(50);
        $view->flats_pager = $flats->pagination_html(50);

        $this->template->content = $view;
        $this->template->title = 'Управление объявлениями';
    }

    function action_add() {
        $this->controller_add();
    }

    function action_delete() {
        $this->controller_delete();
    }

}

?>
