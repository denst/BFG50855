<? class Model_Cities_Area extends ORM {
    
    protected $_table_name = 'cities_areas';
    
    protected $_belongs_to = array(
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'cities_id',
        ),
    );
    
    protected $_has_many = array(
        'adverts' => array(
            'model' => 'advert',
            'foreign_key' => 'cities_areas_id',
        ),
    );
    
    public $_add = array('fields' => array('cities_id', 'name', 'en_name', 'rp_name'));

}