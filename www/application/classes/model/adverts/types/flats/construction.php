<?

// Типы построек домов (блок, монолит, кирпич)
class Model_Adverts_Types_Flats_Construction extends ORM {

	protected $_table_name = 'adverts_types_flats_constructiontypes';

	protected $_has_many = array(
		'flats' => array(
            'model' => 'adverts_types_flat',
            'foreign_key' => 'adverts_types_flats_constructiontypes_id',
        ),
    );


}

?>
