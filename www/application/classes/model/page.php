<?

class Model_Page extends ORM {

	protected $_table_name = 'pages';

	protected $_belongs_to = array(
		'user' => array(
            'model' => 'user',
            'foreign_key' => 'users_id',
        ),
    );

}

?>
