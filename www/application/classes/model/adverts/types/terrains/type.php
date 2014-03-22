<?

// Типы земли
class Model_Adverts_Types_Terrains_Type extends ORM {

	protected $_table_name = 'adverts_types_terrains_types';

	protected $_has_many = array(
		'terrains' => array(
            'model' => 'adverts_types_terrain',
            'foreign_key' => 'adverts_types_terrains_types_id',
        ),
    );

}

?>
