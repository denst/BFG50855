<?

class Controller_posts extends Template {

    // Показать список объявлений, либо конкретное объявление
    function action_show() {
        
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
            $this->template->content = $view;
            $this->template->title = $advert->title;

        } else {

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

            $advert->filterByLocation($this->location);
            $advert->order_by('time','desc');
            
            $view = View::factory('pages/posts/list');
            $view->adverts = $advert->pagination(15);
            $view->pager = $advert->pagination_html(15);
            $view->cat = $cat;
            $view->type = $type;

            $this->template->content = $view;
            $this->template->title = 'Объявления в ' . $this->location->name_rp;

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
            $view->price_types = Arr::Make2Array(ORM::factory('price')->find_all(),'id','name');
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
