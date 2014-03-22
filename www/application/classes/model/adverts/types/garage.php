<?

// Гаражи
class Model_Adverts_Types_Garage extends ORM {

	protected $_table_name = 'adverts_types_garages';

	protected $_belongs_to = array(
		'advert' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_id',
        ),
		'type' => array(
            'model' => 'adverts_types_garages_type',
            'foreign_key' => 'garages_types_id',
        ),
    );

}

?>
