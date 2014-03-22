<?

class Model_Advert extends ORM {

    protected $_table_name = 'adverts';
	public $_add = array('fields' => array('title','desc','alt','users_id'));

    protected $_has_many = array(
		'photos' => array(
            'model'         => 'file',
            'through'       => 'adverts_has_files',
            'foreign_key'   => 'adverts_id',
            'far_key'       => 'files_id',
        ),
    );

    protected $_belongs_to = array(
		'type' => array(
            'model' => 'adverts_type',
            'foreign_key' => 'adverts_types_id',
        ),
		'category' => array(
            'model' => 'adverts_category',
            'foreign_key' => 'adverts_categories_id',
        ),
		'price_type' => array(
            'model' => 'price',
            'foreign_key' => 'price_types_id',
        ),
		'city' => array(
            'model' => 'city',
            'foreign_key' => 'cities_id',
        ),
		'user' => array(
            'model' => 'user',
            'foreign_key' => 'users_id',
        ),
		'photo' => array(
            'model' => 'file',
            'foreign_key' => 'main_photo_id',
        ),
		'area' => array(
            'model' => 'cities_area',
            'foreign_key' => 'cities_areas_id',
        ),
    );

	protected $_has_one = array(
		'flat' => array(
            'model' => 'adverts_types_flat',
            'foreign_key' => 'adverts_id',
        ),
		'house' => array(
            'model' => 'adverts_types_house',
            'foreign_key' => 'adverts_id',
        ),
		'terrain' => array(
            'model' => 'adverts_types_terrain',
            'foreign_key' => 'adverts_id',
        ),
		'garage' => array(
            'model' => 'adverts_types_garage',
            'foreign_key' => 'adverts_id',
        ),
		'room' => array(
            'model' => 'adverts_types_room',
            'foreign_key' => 'adverts_id',
        ),
		'commerce' => array(
            'model' => 'adverts_types_commerce',
            'foreign_key' => 'adverts_id',
        ),
    );

    public function avatar($link = true) {
        if ($this->photo->loaded())
            if ($link)
                return '<a href="'.$this->link().'"><div class="avatar" style="background: url('.$this->photo->thumb().')"></div></a>';
            else
                return '<div class="avatar" style="background: url('.$this->photo->thumb().')"></div>';
        else
            return '';
    }

    public function link() {
        $type = '';
        switch ($this->adverts_types_id) {
            case ADVERTS_TYPES_PRODAJA:
                $type = ADVERTS_TYPES_PRODAJA_STR;
            break;
            case ADVERTS_TYPES_ARENDA:
                $type = ADVERTS_TYPES_ARENDA_STR;
            break;
        }

        switch ($this->adverts_categories_id) {
            case ADVERTS_CATS_FLAT:
                $cat = ADVERTS_CATS_FLAT_STR;
            break;
            case ADVERTS_CATS_HOUSE:
                $cat = ADVERTS_CATS_HOUSE_STR;
            break;
            case ADVERTS_CATS_TERRAIN:
                $cat = ADVERTS_CATS_TERRAIN_STR;
            break;
            case ADVERTS_CATS_GARAGE:
                $cat = ADVERTS_CATS_GARAGE_STR;
            break;
            case ADVERTS_CATS_ROOM:
                $cat = ADVERTS_CATS_ROOM_STR;
            break;
            case ADVERTS_CATS_COMMERCE:
                $cat = ADVERTS_CATS_COMMERCE_STR;
            break;
        }
        
        if ($cat == ADVERTS_CATS_COMMERCE_STR) {
            return 'http://' . $this->city->domain . '.realtynova.' . $this->city->region->country->domain . '/' . $cat . '/' . $type . '/' . $this->commerce->type->lat_name . '/' . $this->id;
        } else {
            return 'http://' . $this->city->domain . '.realtynova.' . $this->city->region->country->domain . '/' . $cat . '/' . $type . '/' . $this->id;
        }
    }

    function filterByLocation($location, $cities_areas_id = false) {
        if (!empty($location->city)) {
            $this->where('cities_id','=',$location->city->id);
            if($cities_areas_id)
                $this->where('cities_areas_id','=', $cities_areas_id);
        } elseif (!empty($location->region)) {
            $this->where('cities_id','IN',db::expr(
                '('.
                Arr::ListArray(Arr::Make1Array($location->region->cities->find_all(),'id'))
                .')'
            ));
        } else {
            
            $regions = $location->country->regions->find_all();
            $cities = array();
            foreach($regions as $region) {
                $_cities = Arr::Make1Array($region->cities->find_all(),'id');
                foreach($_cities as $_city) {
                    array_push($cities,$_city);
                }
            }
            
            $this->where('cities_id','IN',db::expr(
                '('.Arr::ListArray($cities).')'
            ));
        }
        
        return $this;
    }

    /**
     * Полное удаление объявлений
     */
    function fullDelete() {
        $photos = $this->photos->find_all();
        foreach($photos as $photo) {
            $photo->delete();
        }
        $this->delete();
    }

}

?>
