<?

// Типы полов
class Model_Adverts_Types_Flats_Floor extends ORM {

	protected $_table_name = 'adverts_types_flats_floortypes';

	protected $_has_many = array(
		'flats' => array(
            'model' => 'adverts_types_flat',
            'foreign_key' => 'adverts_types_flats_floortypes_id',
        ),
    );


}

?>
