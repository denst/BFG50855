<?

// Тип дома (дом, дача, таунхаус, коттедж)
class Model_Adverts_Types_Houses_Type extends ORM {

	protected $_table_name = 'adverts_types_houses_types';

	protected $_has_many = array(
		'houses' => array(
            'model' => 'adverts_types_house',
            'foreign_key' => 'adverts_types_houses_types_id',
        ),
    );

}

?>
