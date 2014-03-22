<?

// Типы гаражей (гараж, машиноместо)
class Model_Adverts_Types_Garages_Type extends ORM {

	protected $_table_name = 'adverts_types_garages_types';

	protected $_has_many = array(
		'garages' => array(
            'model' => 'adverts_types_garage',
            'foreign_key' => 'garages_types_id',
        ),
    );

}

?>
