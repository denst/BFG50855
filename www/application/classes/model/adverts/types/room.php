<?

// Квартира
class Model_Adverts_Types_Room extends ORM {

	protected $_table_name = 'adverts_types_rooms';

	protected $_belongs_to = array(
		'advert' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_id',
        ),
		'type' => array(
            'model' => 'adverts_types_flats_type',
            'foreign_key' => 'adverts_types_flats_types_id',
        ),
		'construction' => array(
            'model' => 'adverts_types_flats_construction',
            'foreign_key' => 'adverts_types_flats_contructiontypes_id',
        ),
		'pol' => array(
            'model' => 'adverts_types_flats_floor',
            'foreign_key' => 'adverts_types_flats_floortypes_id',
        ),
		'wc' => array(
            'model' => 'adverts_types_flats_wc',
            'foreign_key' => 'adverts_types_flats_wctypes_id',
        ),
    );

}

?>
