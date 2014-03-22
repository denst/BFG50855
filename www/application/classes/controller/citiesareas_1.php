<? class Controller_Citiesareas extends Main {
    public $template = 'admin';
    protected $document_template = 'admin';

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание нового района',
                'button' => 'Создать',
            ),
            'edit' => array(
                'caption' => 'Редактирование района "' . (string)$model->name . '"',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление района "' . (string)$model->name . '"',
                'answer' => 'Вы в курсе, что вы удаляете район "' . (string)$model->name . '"?',
                'button' => 'Удалить район',
            ),
        );
    }
    
	function before() {
		parent::before();
		$this->checkAdminLogin();
	}

	function action_list() {
        $city = ORM::factory('city',$this->request->param('id'));

        if (!$city->loaded())
            throw new HTTP_Exception_404;

        $areas = ORM::factory('cities_area')->where('cities_id','=',$city->id);

		$view = View::factory('pages/cities_areas/list');
    	$view->areas = $areas->pagination(50);
		$view->pager = $areas->pagination_html(50);
        $view->cities = Arr::Make2Array(ORM::factory('city')->where('regions_id','=',$city->regions_id)->find_all(),'id','name');
        $view->city = $city;

		$this->template->content = $view;
		$this->template->title = 'Управление районами города ' . $city->name;
	}

	function action_add() {
        $city = ORM::factory('city',$this->request->param('id'));
        $area_name = Arr::get($_POST,'name');
        $area_names = Arr::get($_POST,'items');
        if ($city->loaded() && ($area_name || $area_names)) {
            if ($area_name) {
                $area = ORM::factory('cities_area');
                $area->cities_id = $city->id;
                $area->name = $area_name;
                $area->save();
            } else {
                $area_names = explode("\n",$area_names);
                foreach($area_names as $area_name) {
                    $area = ORM::factory('cities_area');
                    $area->cities_id = $city->id;
                    $area->name = $area_name;
                    $area->save();
                }
            }
            
            $this->SendJSONData(array(
                JSONA_REFRESHPAGE => '',
            ));
        } else {
            $this->SendJSONData(array(
                JSONA_ERROR => 'Ошибка добавления района',
            ));
        }
	}

	function action_delete() {
        $area = ORM::factory('cities_area',$this->request->param('id'));
        if ($area->loaded()) {
            $area->delete();
            $this->SendJSONData(array(
                JSONA_REFRESHPAGE => '',
            ));
        }
	}
}