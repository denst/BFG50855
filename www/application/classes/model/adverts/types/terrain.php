<?

// Земли
class Model_Adverts_Types_Terrain extends ORM {

	protected $_table_name = 'adverts_types_terrains';

	protected $_belongs_to = array(
		'advert' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_id',
        ),
		'type' => array(
            'model' => 'adverts_types_terrains_type',
            'foreign_key' => 'adverts_types_terrains_types_id',
        ),
		'property' => array(
            'model' => 'adverts_types_terrains_property',
            'foreign_key' => 'adverts_types_terrains_properties_types_id',
        ),
    );

}

?>
