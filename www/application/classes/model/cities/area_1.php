<? class Model_Cities_Area extends ORM {
    protected $_table_name = 'cities_areas';
	protected $_belongs_to = array(
		'city' => array(
            'model' => 'city',
            'foreign_key' => 'cities_id',
        ),
    );
}