<?

class Controller_Ad extends main {

    public $template = 'admin';
    protected $document_template = 'admin';

    protected $_model_name = 'ad';
    protected $_add = array('name', 'countries_id', 'position_id', 'code');

    protected $_messages = array(
        'add' => array(
            'success' => 'Реклама создана',
            'error' => 'Ошибка создания рекламы',
        ),
        'edit' => array(
            'success' => 'Реклама отредактрована',
            'error' => 'Ошибка редактрования рекламы',
        ),
        'delete' => array(
            'success' => 'Реламы удалены',
            'error' => 'Ошибка удаления рекламы',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание новой страницы шаблон районов',
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
        $view = View::factory('pages/ad/list');
        $view->countries = ORM::factory('country')->find_all();
        foreach($view->countries as $country) {
            $ads = ORM::factory('ad')->where('countries_id','=',$country->id)->order_by('id', 'DESC');
            $view->set('ads_'.$country->id,$ads->pagination(100));
            $view->set('ader_'.$country->id,$ads->pagination_html(100));
        }
        $view->positions = ORM::factory('adposition')->find_all();
        $this->template->content = $view;
        $this->template->title = 'Управление рекламными блоками';
    }

	function action_add()
	{
        $this->controller_add();
	}

	function action_edit()
	{
        $this->controller_edit();
	}

        function action_deleteitems() {
            if (Security::check_token()) {

                $ads = Arr::get($_POST,'ads');
                foreach($ads as $ad_id) {
                    ORM::factory('ad',$ad_id)->delete();
                }

                $this->SendJSONData(array(
                    JSONA_COMPLETED => 'Реклама успешно удалена',
                    JSONA_REFRESHPAGE => '',
                ));
            }
        }


}

?>

