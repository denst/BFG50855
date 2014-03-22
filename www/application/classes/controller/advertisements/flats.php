<?

class Controller_Advertisements_Flats extends main {

    public $template = 'admin';
    protected $document_template = 'admin';

    function before() {
        parent::before();
        $this->checkLogin();
    }

    function action_index() {
        $this->checkAdminLogin();
        $flats = ORM::factory('adverts_types_flat');

        $view = View::factory('pages/advertisements/flats/admin');
        $view->flats = $flats->pagination(50);
        $view->flats_pager = $flats->pagination_html(50);

        $this->template->content = $view;
        $this->template->title = 'Управление квартирами';
    }

    function action_add() {

        if ($this->isJson()) {

            if (Security::check_token()) {

                // Основное объявление
                $advert = ORM::factory('advert');
                $advert->title = Arr::get($_POST,'title');
                $advert->desc = Arr::get($_POST,'desc');
                $advert->users_id = $this->user->id;
                $advert->adverts_types_id = Arr::get($_POST,'types_id');        // Продам-сдам в аренду и т.д.
                $advert->adverts_categories_id = ADVERTS_CATS_FLAT;
                $advert->price = Arr::get($_POST,'price');
                $advert->price_types_id = Arr::get($_POST,'price_types_id');
                $advert->cities_id = Arr::get($_POST,'cities_id');
                $advert->time = time();
                $advert->alt = Text::RusToLat(Arr::get($_POST,'title'));
                $advert->main_photo_id = Arr::get_empty($_POST,'photos-main',!empty($_POST['photos'])? $_POST['photos'][0] : null );
                $advert->save();

                if (isset($_POST['photos']))
                    $advert->add('photos',$_POST['photos']);

                // Дополнительная информация о объявлении
                $flat = ORM::factory('adverts_types_flat');
                $flat->adverts_id = $advert->id;
                $flat->adverts_types_flats_types_id = Arr::get($_POST,'flats_types_id');
                $flat->adverts_types_flats_contructiontypes_id = Arr::get($_POST,'constructions_id');
                $flat->rooms = Arr::get($_POST,'rooms');
                $flat->floor = Arr::get($_POST,'floor');
                $flat->floors = Arr::get($_POST,'floors');
                $flat->square = Arr::get($_POST,'square');
                $flat->adres = Arr::get($_POST,'adres');
                $flat->srokarendy = Arr::get($_POST,'srokarendy');
                $flat->save();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => 'Объявление успешно создано',
                    JSONA_REDIRECT => '/advertisements/my'
                ));

            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Ошибка секретного ключа. Пожалуйста, обновите страницу и повторите попытку'
                ));
            }

        } else {

            $view = View::factory('pages/advertisements/flats/edit');
            $view->flat = ORM::factory('adverts_types_flat');
            $view->types = Arr::Make2Array(ORM::factory('adverts_type')->find_all(),'id','name');
            $view->constructions = Arr::Make2Array(ORM::factory('adverts_types_flats_construction')->find_all(),'id','name');
            $view->flats_types = Arr::Make2Array(ORM::factory('adverts_types_flats_type')->find_all(),'id','name');
            $view->price_types = Arr::Make2Array(ORM::factory('price')->find_all(),'id','name');
            $view->cities = Arr::Make2Array(ORM::factory('city')->find_all(),'id','name');

            $this->template->content = $view;
            $this->template->title = 'Добавить квартиру';

        }
    }

    function action_edit() {

        $flat = ORM::factory('adverts_types_flat',$this->request->param('id'));
        $advert = $flat->advert;

        if (!$flat->loaded() || ($advert->users_id != $this->user->id && !$this->user->has('roles',ROLE_ADMIN)))
            throw new HTTP_Exception_404;

        if ($this->isJson()) {

            if (Security::check_token()) {

                // Основное объявление
                $advert->title = Arr::get($_POST,'title');
                $advert->desc = Arr::get($_POST,'desc');
                $advert->adverts_types_id = Arr::get($_POST,'types_id');
                $advert->adverts_categories_id = ADVERTS_CATS_FLAT;
                $advert->price = Arr::get($_POST,'price');
                $advert->price_types_id = Arr::get($_POST,'price_types_id');
                $advert->cities_id = Arr::get($_POST,'cities_id');
                $advert->time = time();
                $advert->alt = Text::RusToLat(Arr::get($_POST,'title'));
                $advert->main_photo_id = Arr::get($_POST,'photos-main');
                $advert->save();

                $advert->remove('photos');

                if (isset($_POST['photos']))
                    $advert->add('photos',$_POST['photos']);

                // Дополнительная информация о объявлении
                $flat->adverts_types_flats_types_id = Arr::get($_POST,'flats_types_id');
                $flat->adverts_types_flats_contructiontypes_id = Arr::get($_POST,'constructions_id');
                $flat->rooms = Arr::get($_POST,'rooms');
                $flat->floor = Arr::get($_POST,'floor');
                $flat->floors = Arr::get($_POST,'floors');
                $flat->square = Arr::get($_POST,'square');
                $flat->adres = Arr::get($_POST,'adres');
                $flat->srokarendy = Arr::get($_POST,'srokarendy');
                $flat->save();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => 'Объявление успешно обновлено',
                    JSONA_REDIRECT => '/advertisements/my'
                ));

            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Ошибка секретного ключа. Пожалуйста, обновите страницу и повторите попытку'
                ));
            }

        } else {

            $view = View::factory('pages/advertisements/flats/edit');
            $view->flat = $flat;
            $view->types = Arr::Make2Array(ORM::factory('adverts_type')->find_all(),'id','name');
            $view->constructions = Arr::Make2Array(ORM::factory('adverts_types_flats_construction')->find_all(),'id','name');
            $view->flats_types = Arr::Make2Array(ORM::factory('adverts_types_flats_type')->find_all(),'id','name');
            $view->price_types = Arr::Make2Array(ORM::factory('price')->find_all(),'id','name');
            $view->cities = Arr::Make2Array(ORM::factory('city')->find_all(),'id','name');

            $this->template->content = $view;
            $this->template->title = 'Изменить объявление';

        }
    }

    function action_delete() {

        $flat = ORM::factory('adverts_types_flat',$this->request->param('id'));
        $advert = $flat->advert;

        if (!$flat->loaded() || ($advert->users_id != $this->user->id && !$this->user->has('roles',ROLE_ADMIN)))
            throw new HTTP_Exception_404;

        if ($this->isJson()) {

            if (Security::check_token()) {

                $photos = $flat->advert->photos->find_all();

                foreach($photos as $photo) {
                    $photo->delete();
                }

                $flat->advert->delete();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => 'Объявление успешно удалено',
                    JSONA_REDIRECT => '/advertisements/my'
                ));


            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Ошибка секретного ключа. Пожалуйста, обновите страницу и повторите попытку'
                ));
            }


        } else {

            $view = View::factory('pages/advertisements/flats/delete');
            $view->flat = $flat;

            $this->template->content = $view;
            $this->template->title = 'Удалить объявление';

        }


    }


}

?>
