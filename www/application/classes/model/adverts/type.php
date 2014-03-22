<?

// Типы предложений - продажа, аренда, и т.д.
class Model_Adverts_Type extends ORM {

	protected $_table_name = 'adverts_types';

	protected $_has_many = array(
		'adverts' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_types_id',
        ),
    );


}

?>
