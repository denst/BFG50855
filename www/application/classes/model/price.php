<?

class Model_Price extends ORM {

	protected $_table_name = 'price_types';

	protected $_has_many = array(
		'adverts' => array(
            'model' => 'advert',
            'foreign_key' => 'price_types_id',
        ),
    );
    
    public function get_all_currencies()
    {
        return ORM::factory('price')->find_all();
    }
    
    public function update_course($courses)
    {
        $currencies = ORM::factory('price')->find_all();
        for ($i = 0; $i < count($currencies); $i++) 
        {
            if($currencies[$i]->name != 'руб.')
            {
                try 
                {
                    $course = str_replace(',', '.', $courses[$i-1]);
                    ORM::factory('price', $currencies[$i]->id)
                        ->set('course', $course)
                        ->update();
                }
                catch (Exception $exc) 
                {
                    return false;
                }
            }
        }
    }
    
    public static function convert_price($advert, $country)
    {
        if($advert->price_types_id != $country->currency->id)
        {
            if($advert->price_types_id != 1)
            {
                $rub_price = $advert->price / $advert->price_type->course;
            }
            else
            {
                $rub_price = $advert->price;
            }
            
            $price = number_format( $country->currency->course * $rub_price, 0, '.', '' );
        }
        else
            $price = $advert->price;
        
        $currency_name = $country->currency->symbol;
        return $price.' '.$currency_name;
    }
}

?>
