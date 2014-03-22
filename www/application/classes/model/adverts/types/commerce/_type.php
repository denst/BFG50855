<?

// Типы земли
class Model_Adverts_Types_Commerce_Type extends ORM {

	protected $_table_name = 'adverts_types_commerces_types';

	protected $_has_many = array(
		'terrains' => array(
            'model' => 'adverts_types_commerce',
            'foreign_key' => 'adverts_types_commerces_types_id',
        ),
    );

}

?>
