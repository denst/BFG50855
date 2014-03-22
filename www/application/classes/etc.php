<?

// Класс с разными небольшими функциями, которые нам необходимы в разных частях сайта
class etc {

    // Для правильного роутинга. Сгенерировать правило с добавлением всех статических страниц в него
    static function generatePages() {
        $pages = ORM::factory('page')->find_all();
        return '(' . implode('|', Arr::Make1Array($pages, 'lat_name')) . ')';
    }

    // Функция, определяющая, в какой локации мы сейчас находимся и отдающая объект с названиями локации в правильных падежах
    static function identifyLocation() {

        // В переменной лежит массив из текущего домена, который набрал пользователь в браузере, разбитого на части по точкам
        // Используя эту информацию, мы определим локацию человека :)
        $domain = explode('.',Arr::get($_SERVER,'HTTP_HOST'));

        // Объект со всей необходимой информацией о текущей локации. По-умолчанию - Россия
        $info = (object) array();
        $info->search = 'country';
        $info->country = ORM::factory('country',1);
        $info->region = null;
        $info->city = null;
        $info->longitude = 65;
        $info->latitude = 77;
        $info->zoom = 2;
        $info->name = $info->country->name;
        $info->name_rp = $info->country->name_rp;
        $info->name_pp = $info->country->name_pp;

        // Если клиент зашел на основной домен, без субдоменов
        if (in_array($domain[0],array('realty','realtynova','realtynova-new'))) {
            // Определим страну по зоне домена :)
            if (count($domain) == 1 || $domain[1] == 'ru') {
                // Россия
                // Это уже было сделано наверху, поэтому здесь ничего не делаем :)
                //$info->country = ORM::factory('country',1);
            } elseif ($domain[1] == 'com' && $domain[2] == 'ua') {
                // Украина
                $info->country = ORM::factory('country',3);
                $info->name = $info->country->name;
                $info->name_rp = $info->country->name_rp;
                $info->name_pp = $info->country->name_pp;
            } elseif ($domain[1] == 'kz') {
                // Казахстан
                $info->country = ORM::factory('country',4);
                $info->name = $info->country->name;
                $info->name_rp = $info->country->name_rp;
                $info->name_pp = $info->country->name_pp;
            } elseif ($domain[1] == 'by') {
                // Беларусь
                $info->country = ORM::factory('country',5);
                $info->name = $info->country->name;
                $info->name_rp = $info->country->name_rp;
                $info->name_pp = $info->country->name_pp;
            }
        } else {
			$country_domain = $domain[count($domain)-1];

            // Регион
            $info->region = ORM::factory('region',array('domain' => $domain[0]));
            if ($info->region->loaded()) {
                $info->country = $info->region->country;
                $info->search = 'region';
                $info->longitude = $info->region->longitude;
                $info->latitude = $info->region->latitude;
                $info->zoom = 5;
                $info->name = $info->region->name;
                $info->name_rp = $info->region->name_rp;
                $info->name_pp = $info->region->name_pp;

				$current_country_domain = explode('.',$info->country->domain);
				$current_country_domain = $current_country_domain[count($current_country_domain)-1];

				if ($current_country_domain != $country_domain) {
					//if ($country_domain == 'ua') $country_domain = 'com.ua';
					//Request::current()->redirect('http://realtynova.' . $country_domain);
					Request::current()->redirect('http://realtynova.ru');
				}
            } else {
                // Город
                $info->city = ORM::factory('city',array('domain' => $domain[0]));
                if ($info->city->loaded()) {
                    $info->region = $info->city->region;
                    $info->country = $info->city->region->country;
                    $info->search = 'city';
                    $info->longitude = $info->city->longitude;
                    $info->latitude = $info->city->latitude;
                    $info->zoom = 10;
                    $info->name = $info->city->name;
                    $info->name_rp = $info->city->name_rp;
                    $info->name_pp = $info->city->name_pp;

					$current_country_domain = explode('.',$info->country->domain);
					$current_country_domain = $current_country_domain[count($current_country_domain)-1];

					if ($current_country_domain != $country_domain) {
						//if ($country_domain == 'ua') $country_domain = 'com.ua';
						//Request::current()->redirect('http://realtynova.' . $country_domain);
						Request::current()->redirect('http://realtynova.ru');
					}
				} else {
					//if ($country_domain == 'ua') $country_domain = 'com.ua';
					//Request::current()->redirect('http://realtynova.' . $country_domain);
					Request::current()->redirect('http://realtynova.ru');
				}
            }
        }
        return $info;
    }

    static function identifySeo($country_id, $url = null) {
        if ($url === NULL) $url = Request::current()->uri();
        $model = ORM::factory('seo')
                ->where('url','=',$url)
                ->where('countries_id','=',$country_id)
                ->find();
        $model->setReplace(true);
        
        if (isset($_GET['page']) && $_GET['page'] != '1') {
            $model->main = '';
            $model->footer = '';
        }
        
        return $model;
    }
    
    static function identifySeoDistricts($country_id, $area, $url = null) {
        if ($url === NULL) $url = Request::current()->uri();
        $model = ORM::factory('dist')
                ->where('url','=',$url)
                ->where('countries_id','=',$country_id)
                ->find();
        $model->setReplace(true, $area);
        
        if (isset($_GET['page']) && $_GET['page'] != '1') {
            $model->main = '';
            $model->footer = '';
        }
        
        return $model;
    }
    
//    static function identifySeoDistricts($country_id, $area) {
//        $model = ORM::factory('dist')
//                ->where('countries_id','=',$country_id)
//                ->find();
//        $model->setReplace(true, $area);
//        
//        if (isset($_GET['page']) && $_GET['page'] != '1') {
//            $model->main = '';
//            $model->footer = '';
//        }
//        
//        return $model;
//    }

    static function active($url,$text) {
        if (Request::current()->url() == $url) {
            return $text;
        };

        return '';
    }
    
    static function postsName($cat,$type) {
        switch ($cat) {
            case ADVERTS_CATS_FLAT:
                $cat = ' квартир';
            break;
            case ADVERTS_CATS_HOUSE:
                $cat = ' домов';
            break;
            case ADVERTS_CATS_TERRAIN:
                $cat = " земель";
            break;
            case ADVERTS_CATS_GARAGE:
                $cat = " гаражей";
            break;
            case ADVERTS_CATS_ROOM:
                $cat = " комнат";
            break;
            default :
                $cat = ", объявления";
            break;
        }
        switch ($type) {
            case ADVERTS_TYPES_PRODAJA:
                $type = "Продажа";
            break;
            case ADVERTS_TYPES_ARENDA:
                $type = "Аренда";
            break;
            default :
                $type = "Продажа и аренда";
            break;
        }
        return $type . $cat;
    }
}