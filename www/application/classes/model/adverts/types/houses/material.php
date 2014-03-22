<?

// Тип материалов домов и коттеджей
class Model_Adverts_Types_Houses_Material extends ORM {

	protected $_table_name = 'adverts_types_houses_materials';

	protected $_has_many = array(
		'houses' => array(
            'model' => 'adverts_types_house',
            'foreign_key' => 'adverts_types_houses_materials_id',
        ),
    );

}

?>
