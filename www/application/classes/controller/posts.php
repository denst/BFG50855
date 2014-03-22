<?

class Controller_posts extends Template {

    // Показать список объявлений, либо конкретное объявление
    function action_show() {
        switch ($this->request->param('cat')) {
            case ADVERTS_CATS_FLAT_STR:
                $cat = ADVERTS_CATS_FLAT;
            break;
            case ADVERTS_CATS_HOUSE_STR:
                $cat = ADVERTS_CATS_HOUSE;
            break;
            case ADVERTS_CATS_TERRAIN_STR:
                $cat = ADVERTS_CATS_TERRAIN;
            break;
            case ADVERTS_CATS_GARAGE_STR:
                $cat = ADVERTS_CATS_GARAGE;
            break;
            case ADVERTS_CATS_ROOM_STR:
                $cat = ADVERTS_CATS_ROOM;
            break;
            case ADVERTS_CATS_COMMERCE_STR:
                $cat = ADVERTS_CATS_COMMERCE;
            break;
            default :
                $cat = null;
            break;
        }

        switch ($this->request->param('type')) {
            case ADVERTS_TYPES_PRODAJA_STR:
                $type = ADVERTS_TYPES_PRODAJA;
            break;
            case ADVERTS_TYPES_ARENDA_STR:
                $type = ADVERTS_TYPES_ARENDA;
            break;
            default :
                $type = null;
            break;
        }
        
        $advert = ORM::factory('advert',$this->request->param('id'));

        if ($advert->loaded()) {
            

            // Запретим отображение любого конкретного объявления на домене страны или региона: смореть можно только на домене города
            // а также если кто-то пытается вытащить объявление одного города на домене другого
            if (in_array($this->location->search, array('country','region'))
                || $this->location->name != $advert->city->name) {
                throw new HTTP_Exception_404;
            }
            
            $this->seo = etc::identifySeo($this->location->country->id,$this->request->param('cat').'/'.$this->request->param('type'));
            View::set_global('_seo', $this->seo);
            
            $view = View::factory('pages/posts/show');
            $view->advert = $advert;
            
            if(is_object($this->location->country))
            {
                $view->country = $this->location->country;
            }
            
            $view->cat_link = $this->request->param('cat');
            $view->cat_name = Model::factory('adverts_category')
                ->get_inflected_category_name($cat);
            $view->type_link = $this->request->param('type');
            $view->type_name = ORM::factory('adverts_type', $type)->name.' в '.
                $advert->city->name_rp;
            $view->advert_breadcrumb = 'Объявление № '.$advert->id;
            
            if (Route::name($this->request->route()) == 'commerce_adverts') {
                $commtype = $this->request->param('commtype');
                $view->commtype_link = $commtype;
                $view->commtype_name = ORM::factory('adverts_types_commerce_type')
                        ->where('lat_name','=',$commtype)->find()->name;
            }
            if(isset($commtype) AND Valid::not_empty($commtype))
            {
                $view->commtype_link = $this->request->param('commtype');
                $view->commtype_name = ORM::factory('adverts_types_commerce_type')
                        ->where('lat_name','=',$commtype)->find()->name;
            }
            
            $this->template->content = $view;
            View::set_global('advert_title', Text::limit_chars($advert->desc, 120));

        } else {

            if (!empty($cat))
                $advert->where('adverts_categories_id','=',$cat);
            if (!empty($type))
                $advert->where('adverts_types_id','=',$type);
            if (Route::name($this->request->route()) == 'commerce_adverts') {
                $commtype = $this->request->param('commtype');
                if ($commtype) {
                    $advert->with('commerce:type')->where('commerce:type.lat_name','=',$commtype);
                }
            }
            $view = View::factory('pages/posts/list');

            $is_loaded_current_area = false;  
            
            if(is_object($this->location->city))
            {
                $en_names = array();
                $areas = $this->location->city->areas->find_all();
                foreach ($areas as $area) 
                {
                    $en_names[] = $area->en_name;
                }
                
                if(in_array($this->request->param('id'), $en_names)
                    OR in_array($this->request->param('type'), $en_names))
                {
                    $set_arrea = true;
                    if(is_null($type))
                        $current_area_name = $this->request->param('type');
                    else
                        $current_area_name = $this->request->param('id');
                       
                    $current_area = ORM::factory('cities_area')
                            ->where('en_name', '=', $current_area_name)
                            ->find();
                    
                    if($current_area->loaded())
                    {
                        $view->area_name = 'Район '.$current_area->name;
                        $advert->filterByLocation($this->location, $current_area->id);
                        $is_loaded_current_area = true;
                        $view->set_current_arrea = true;
                        
                        $this->seo = etc::identifySeoDistricts($this->location->country->id, $current_area, $current_area->en_name);
                        View::set_global('_seo', $this->seo);
                        View::set_global('_seo_footer', $this->seo->footer);
                    }
                    else {
                        throw new HTTP_Exception_404;
                    }
                }
            }
            
            if(Valid::not_empty($this->request->param('id'))
                    AND !isset($set_arrea))
                throw new HTTP_Exception_404;
            
            if(is_object($this->location->country))
            {
                $view->country = $this->location->country;
                $view->ads = ORM::factory('ad')->where('countries_id', '=', $this->location->country->id)->find_all();
            }
            
            if(! $is_loaded_current_area)
            {
                $advert->filterByLocation($this->location);
            }
            
            $advert->order_by('time','desc');
            $view->adverts = $advert->pagination(15);
            
            if(Settings::instance()->get_setting('empty_areas') == 'on')
            {
                if(is_object($this->location->city))
                    $view->areas = $this->location->city->areas->find_all();
                else
                    $view->areas = array();
            }
            else
            {
                $result_areas = array();
                foreach ($view->adverts as $advert) {
                    $result_areas[] = $advert->area;
                }
                $view->areas = $result_areas;
            }

            
            $view->pager = $advert->pagination_html(15);
            
            $view->cat = $cat;
            $view->cat_link = $this->request->param('cat');
            $view->cat_name = Model::factory('adverts_category')
                    ->get_inflected_category_name($cat);
            $view->type = $type;
            
            if(!empty($type))
            {
                if(Valid::not_empty($this->location->name))
                    $view->type_name = ORM::factory('adverts_type', $type)->name.' в '.
                       $this->location->name_rp;
                else
                    $view->type_name = ORM::factory('adverts_type', $type)->name;
                
                if(isset($commtype) AND Valid::not_empty($commtype))
                {
                    $commerce_type = ORM::factory('adverts_types_commerce_type')
                            ->where('lat_name','=',$commtype)->find();
                    $view->commtype_name = $commerce_type->name;
                    $view->commtype_link = $commerce_type->lat_name;
                }
                $view->type_link = $this->request->param('type');
            }

            $this->template->content = $view;

        }
    }

    // Список моих объявлений
    function action_my() {
        $this->checkLogin();

        $view = View::factory('pages/posts/my');
        $view->adverts = ORM::factory('advert')
                ->where('users_id','=',$this->user->id)
                ->order_by('time','desc')
                ->find_all();

        $this->template->content = $view;
        $this->template->title = 'Мои объявления';

    }

    // Создать/отредактировать объявление
    function action_save() {
        $this->checkLogin();
        $advert = ORM::factory('advert',$this->request->param('id'));

        $edit = FALSE;

        if ($advert->loaded() && ($advert->users_id == $this->user->id || $this->user->has('roles',ROLE_ADMIN)))
            $edit = TRUE;

        if ($this->isJson()) {
            if (Security::check_token()) {
                $db = Database::instance();
                $db->begin();

                // Основное объявление
                $advert->title = Arr::get($_POST,'title');
                $advert->desc = Arr::get($_POST,'desc');
                $advert->users_id = $this->user->id;
                $advert->adverts_types_id = Arr::get($_POST,'types_id');
                $advert->adverts_categories_id = Arr::get($_POST,'categories_id');
                $advert->price = Arr::get($_POST,'price');
                $advert->price_types_id = Arr::get($_POST,'price_types_id');
                $advert->cities_id = Arr::get($_POST,'cities_id');
                $city_area = Arr::get($_POST,'city_area');
                if ($city_area && ORM::factory('cities_area',array('id'=>$city_area,'cities_id'=>Arr::get($_POST,'cities_id')))->loaded())
                        $advert->cities_areas_id = $city_area;
                $advert->time = time();
                $advert->alt = Text::RusToLat(Arr::get($_POST,'title'));
                $advert->main_photo_id = Arr::get($_POST,'photos-main');
                $advert->square = Arr::get($_POST,'square');
                $advert->adres = Arr::get($_POST,'adres');
                $advert->period = Arr::get($_POST,'period');
                $advert->x = Arr::get($_POST,'points_x');
                $advert->y = Arr::get($_POST,'points_y');
                $advert->phone = Arr::get($_POST,'phone');
                $advert->email = Arr::get($_POST,'email');
                $advert->save();

                $advert->remove('photos');

                if (isset($_POST['photos']))
                    $advert->add('photos',$_POST['photos']);

                // Дополнительная информация о объявлении
                switch ($advert->adverts_categories_id) {
                    case ADVERTS_CATS_FLAT:
                        $advert->flat->adverts_id = $advert->id;
                        $advert->flat->adverts_types_flats_types_id = Arr::get($_POST,'flats_types_id');
                        $advert->flat->adverts_types_flats_contructiontypes_id = Arr::get($_POST,'constructions_id');
                        $advert->flat->adverts_types_flats_floortypes_id = Arr::get($_POST,'adverts_types_flats_floortypes_id');
                        $advert->flat->adverts_types_flats_wctypes_id = Arr::get($_POST,'adverts_types_flats_wctypes_id');
                        $advert->flat->rooms = Arr::get($_POST,'rooms');
                        $advert->flat->floor = Arr::get($_POST,'floor');
                        $advert->flat->floors = Arr::get($_POST,'floors');
                        $advert->flat->lift = Arr::get($_POST,'lift');
                        $advert->flat->ceilingheight = Arr::get($_POST,'ceilingheight');
                        $advert->flat->phone = Arr::get($_POST,'phone');
                        $advert->flat->save();
                    break;
                    case ADVERTS_CATS_ROOM:
                        $advert->room->adverts_id = $advert->id;
                        $advert->room->adverts_types_flats_types_id = Arr::get($_POST,'flats_types_id');
                        $advert->room->adverts_types_flats_contructiontypes_id = Arr::get($_POST,'constructions_id');
                        $advert->room->adverts_types_flats_floortypes_id = Arr::get($_POST,'adverts_types_flats_floortypes_id');
                        $advert->room->adverts_types_flats_wctypes_id = Arr::get($_POST,'adverts_types_flats_wctypes_id');
                        $advert->room->rooms = Arr::get($_POST,'rooms');
                        $advert->room->floor = Arr::get($_POST,'floor');
                        $advert->room->floors = Arr::get($_POST,'floors');
                        $advert->room->lift = Arr::get($_POST,'lift');
                        $advert->room->ceilingheight = Arr::get($_POST,'ceilingheight');
                        $advert->room->phone = Arr::get($_POST,'phone');
                        $advert->room->save();
                    break;
                    case ADVERTS_CATS_HOUSE:
                        $advert->house->adverts_id = $advert->id;
                        $advert->house->adverts_types_houses_materials_id = Arr::get($_POST,'houses_materials_id');
                        $advert->house->adverts_types_houses_types_id = Arr::get($_POST,'houses_types_id');
                        $advert->house->adverts_types_terrains_types_id = Arr::get($_POST,'houses_terrains_id');
                        $advert->house->dogoroda = Arr::get($_POST,'house_dogoroda');
                        $advert->house->save();
                    break;
                    case ADVERTS_CATS_TERRAIN:
                        $advert->terrain->adverts_id = $advert->id;
                        $advert->terrain->adverts_types_terrains_properties_types_id = Arr::get($_POST,'terrains_properties_id');
                        $advert->terrain->adverts_types_terrains_types_id = Arr::get($_POST,'terrains_types_id');
                        $advert->terrain->dogoroda = Arr::get($_POST,'house_dogoroda');
                        $advert->terrain->save();
                    break;
                    case ADVERTS_CATS_GARAGE:
                        $advert->garage->adverts_id = $advert->id;
                        $advert->garage->garages_types_id = Arr::get($_POST,'garages_types_id');
                        $advert->garage->save();
                    break;
                    case ADVERTS_CATS_COMMERCE:
                        $advert->commerce->adverts_id = $advert->id;
                        $advert->commerce->commerce_types_id = Arr::get($_POST,'commerce_types_id');
                        $advert->commerce->properties_types_id = Arr::get($_POST,'properties_types_id');
                        $advert->commerce->save();
                    break;
                }

                $db->commit();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => $edit ? 'Объявление успешно обновлено' : 'Объявление успешно создано',
                    JSONA_REDIRECT => '/posts/my'
                ));
            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Ошибка секретного ключа. Пожалуйста, обновите страницу и повторите попытку'
                ));
            }
        } else {
            $view = View::factory('pages/posts/add_edit');
            $view->advert = $advert;
            $view->types = Arr::Make2Array(ORM::factory('adverts_type')->find_all(),'id','name');
            $view->categories = Arr::Make2Array(ORM::factory('adverts_category')->find_all(),'id','name');
            $view->constructions = Arr::merge(array('' => 'Тип постройки'),Arr::Make2Array(ORM::factory('adverts_types_flats_construction')->find_all(),'id','name'));
            $view->flats_types = Arr::merge(array('' => 'Тип квартиры'),Arr::Make2Array(ORM::factory('adverts_types_flats_type')->find_all(),'id','name'));
            $view->adverts_types_flats_floortypes = Arr::merge(array('' => 'Пол'),Arr::Make2Array(ORM::factory('adverts_types_flats_floor')->order_by('id')->find_all(),'id','name'));
            $view->adverts_types_flats_wctypes = Arr::merge(array('' => 'Санузел'),Arr::Make2Array(ORM::factory('adverts_types_flats_wc')->order_by('id')->find_all(),'id','name'));
            $view->houses_types = Arr::merge(array('' => 'Тип дома'),Arr::Make2Array(ORM::factory('adverts_types_houses_type')->find_all(),'id','name'));
            $view->houses_materials = Arr::merge(array('' => 'Тип материала'),Arr::Make2Array(ORM::factory('adverts_types_houses_material')->find_all(),'id','name'));
            $view->terrain_types = Arr::merge(array('' => 'Вид собственности'),Arr::Make2Array(ORM::factory('adverts_types_terrains_type')->find_all(),'id','name'));
            $view->terrains_properties = Arr::merge(array('' => 'Тип земли'),Arr::Make2Array(ORM::factory('adverts_types_terrains_property')->find_all(),'id','name'));
            $view->garages_types = Arr::merge(array('' => 'Тип гаража'),Arr::Make2Array(ORM::factory('adverts_types_garages_type')->find_all(),'id','name'));
            $view->commerce_types = Arr::merge(array('' => 'Тип недвижимости'),Arr::Make2Array(ORM::factory('adverts_types_commerce_type')->find_all(),'id','name'));
            $view->commerce_properties = Arr::merge(array('' => 'Уровень недвижипости'),Arr::Make2Array(ORM::factory('adverts_types_commerce_property')->find_all(),'id','name'));
            $view->price_types = Arr::Make2Array(ORM::factory('price')->find_all(),'id','symbol');
            $view->cities = Arr::merge(array('' => 'Выберите город'),Arr::Make2Array(ORM::factory('city')->order_by('name')->find_all(),'id','name'));

            $this->template->content = $view;
            $this->template->title = $edit ? 'Изменить объявление' : 'Создать объявление';
        }
    }

    function action_delete() {
        $this->checkLogin();

        $advert = ORM::factory('advert',$this->request->param('id'));

        if (!$advert->loaded() || ($advert->users_id != $this->user->id && !$this->user->has('roles',ROLE_ADMIN)))
            throw new HTTP_Exception_404;

        if ($this->isJson()) {

            if (Security::check_token()) {

                $advert->fullDelete();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => 'Объявление успешно удалено',
                    JSONA_REDIRECT => '/posts/my'
                ));


            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Ошибка секретного ключа. Пожалуйста, обновите страницу и повторите попытку'
                ));
            }


        } else {

            $view = View::factory('pages/posts/delete');
            $view->advert = $advert;

            $this->template->content = $view;
            $this->template->title = 'Удалить объявление';

        }


    }

}

?>
