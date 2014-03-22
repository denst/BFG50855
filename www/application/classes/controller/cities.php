<? class Controller_Cities extends Main {
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
            'tag' => 'hidden',
            'label' => '',
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

	function action_list() {
		$this->checkAdminLogin();
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

	function action_add() {
		$this->checkAdminLogin();
        $this->controller_add(array(
            'regions_id' => $this->request->param('id')
        ));
	}

	function action_edit() {
		$this->checkAdminLogin();
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

	function action_delete() {
		$this->checkAdminLogin();
        $this->controller_delete();
	}
    
        function action_container() {
            $this->auto_render = false;
            $this->response->body(View::factory('pages/cities/container'));
        }
    
        function action_getcities()
        {
            if ($this->request->is_ajax())
            {
                $result_cities = '';
                $cities = ORM::factory('city')->where('regions_id','=', $_POST['id'])->find_all();
                $count_cities = ORM::factory('city')->where('regions_id','=', $_POST['id'])->count_all();
                foreach ($cities as $city) 
                {
                    $result_cities .= $city->name."\n";
                }
                $result = array(
                    'success' => true, 
                    'result_cities' => $result_cities,
                    'count_cities' => $count_cities
                );
                echo json_encode($result);
            }
            exit();
        }
        
        function action_getcityinfo()
        {
            if ($this->request->is_ajax())
            {
                $result = array();
                $result['cities'] = array();
                $cities_names = explode("\n", $_POST['cities']);
                
                $res_index = 0;
                foreach ($cities_names as $city_name) 
                {
                    $result['cities'][$res_index]['id'] = $res_index;
                    $result['cities'][$res_index]['name'] = $city_name;
                    $location = json_decode(Request::factory('http://geocode-maps.yandex.ru/1.x/?format=json&sco=latlong&geocode='.$city_name)->execute());
                    $inflect = json_decode(Request::factory('http://export.yandex.ru/inflect.xml?&format=json&name='.$city_name)->execute());
                    
                    $pos = explode(' ', $location->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
                    $result['cities'][$res_index]['longitude'] = $pos[0];
                    $result['cities'][$res_index]['latitude'] = $pos[1];
                    
                    $index = 0;
                    foreach ($inflect as $inf) {
                        if($index == 2)
                            $result['cities'][$res_index]['name_pp'] = $inf;
                        elseif($index == 6)
                            $result['cities'][$res_index]['name_rp'] = $inf;
                        $index++;
                    }
                    
                    $result['cities'][$res_index]['domain'] = Model::factory('city')->translit(strtolower($city_name));
                    $res_index++;
                }
                $result['success'] = true;
                echo json_encode($result);
                
            }
            exit();
        }
        
        function action_import() 
        {
            $this->checkAdminLogin();
            if ($this->isJson())
            {
                if (Security::check_token())
                {
                    if(Model::factory('city')->import($_POST))
                    {
                         $this->SendJSONData(array(
                            JSONA_REFRESHPAGE => '',
                            JSONA_COMPLETED => 'Города успешно импортированы',
                        ));
                    }
                    else
                    {
                        $this->SendJSONData(array(
                            JSONA_ERROR => 'Ошибка добавления городов',
                        ));
                    }
                }
                else 
                {
                    $this->SendJSONData(JSONA_ERROR, 'Ошибка валидации сообщения');
                    return false;
                }
            } 
	}

}