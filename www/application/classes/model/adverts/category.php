<?

// Типы недвижимости - гараж, земля, квартира и т.д.
class Model_Adverts_Category extends ORM {
    
        private $category_inflected_name = array(
            1 => 'Квартиры', 2 => 'Комнаты', 
            3 => 'Земельные участки', 4 =>'Гаражи', 5 => 'Дома, коттеджи'
        );

	protected $_table_name = 'adverts_categories';

	protected $_has_many = array(
		'adverts' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_categories_id',
        ),
    );
        
    public function get_inflected_category_name($category_id)
    {
        if(key_exists($category_id, $this->category_inflected_name))
            return $this->category_inflected_name[$category_id];
        else
            return ORM::factory('adverts_category', $category_id)->name;
    }
}

?>
