<?

class Model_Country extends ORM {

    public $_add = array('fields' => array('name','name_rp', 'name_pp','code', 'price_types_id'));

	protected $_table_name = 'countries';

	protected $_has_many = array(
		'regions' => array(
            'model' => 'region',
            'foreign_key' => 'countries_id',
            )
        );
            
	protected $_belongs_to = array(
            'currency' => array(
                'model' => 'price',
                'foreign_key' => 'price_types_id',
            )
        );
        
        
    public function update_currency($currencies)
    {
        $countries = ORM::factory('country')->find_all();
        foreach ($countries as $country)
        {
            try 
            {
                ORM::factory('country', $country->id)
                    ->set('price_types_id', $currencies['currencies_'.$country->id])
                    ->update();
            }
            catch (Exception $exc) 
            {
                return false;
            }
        }
    }
}

?>
