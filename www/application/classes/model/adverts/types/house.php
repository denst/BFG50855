<?

// Дома, коттеджи
class Model_Adverts_Types_House extends ORM {

	protected $_table_name = 'adverts_types_houses';

	protected $_belongs_to = array(
		'advert' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_id',
        ),
		'type' => array(
            'model' => 'adverts_types_houses_type',
            'foreign_key' => 'adverts_types_houses_types_id',
        ),
		'material' => array(
            'model' => 'adverts_types_houses_material',
            'foreign_key' => 'adverts_types_houses_materials_id',
        ),
		'terrain' => array(
            'model' => 'adverts_types_terrains_type',
            'foreign_key' => 'adverts_types_terrains_types_id',
        ),
    );

}

?>
