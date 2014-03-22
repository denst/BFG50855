<?

// Наличие санузела (нет, 1, 2 и т.д.)
class Model_Adverts_Types_Flats_wc extends ORM {

	protected $_table_name = 'adverts_types_flats_wctypes';

	protected $_has_many = array(
		'flats' => array(
            'model' => 'adverts_types_flat',
            'foreign_key' => 'adverts_types_flats_wctypes_id',
        ),
    );
    
}

?>
