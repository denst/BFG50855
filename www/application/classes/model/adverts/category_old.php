<?

// Типы недвижимости - гараж, земля, квартира и т.д.
class Model_Adverts_Category extends ORM {

	protected $_table_name = 'adverts_categories';

	protected $_has_many = array(
		'adverts' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_categories_id',
        ),
    );


}

?>
