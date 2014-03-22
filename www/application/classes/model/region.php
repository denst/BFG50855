<?

class Model_Region extends ORM {

    public $_add = array('fields' => array('name','name_rp', 'name_pp','domain','longitude','latitude','code','countries_id'));

    protected $_table_name = 'regions';

	protected $_belongs_to = array(
		'country' => array(
            'model' => 'country',
            'foreign_key' => 'countries_id',
        ),
    );

	protected $_has_many = array(
		'cities' => array(
            'model' => 'city',
            'foreign_key' => 'regions_id',
        ),
    );

}

?>
