<?

class Model_District extends ORM {

	protected $_table_name = 'districts';

	protected $_has_many = array(
		'cities' => array(
            'model' => 'city',
            'foreign_key' => 'districts_id',
        ),
    );
}

?>
