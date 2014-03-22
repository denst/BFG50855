<?

// Типы квартир (новостройка, вторичное жилье, и т.д.)
class Model_Adverts_Types_Flats_Type extends ORM {

	protected $_table_name = 'adverts_types_flats_types';

	protected $_has_many = array(
		'flats' => array(
            'model' => 'adverts_types_flat',
            'foreign_key' => 'adverts_types_flats_types_id',
        ),
    );


}

?>
