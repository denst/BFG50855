<? class Model_City extends ORM {

    public $_add = array('fields' => array('name','name_rp', 'name_pp','domain','longitude','latitude','regions_id','code'));

    protected $_table_name = 'cities';

	protected $_belongs_to = array(
		'district' => array(
            'model' => 'district',
            'foreign_key' => 'districts_id',
        ),
		'region' => array(
            'model' => 'region',
            'foreign_key' => 'regions_id',
        ),
    );

	protected $_has_many = array(
		'areas' => array(
            'model' => 'cities_area',
            'foreign_key' => 'cities_id',
        ),
    );

    // Вернуть полное название города
    function fullname() {
        return $this->region->name . ' / ' . $this->name;
    }
    
    function translit($string)
    {
        $array = array( 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', '-' => '-',  'д' => 'd',   'е' => 'e',  'ё' => 'e', 'ж' => 'zh',  'з' => 'z', 'и' => 'i', 'й' => 'y',   'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '-','А' => 'a',  'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'e', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'Й' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n',  'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ы' => 'y', 'Э' => 'e', 'Ю' => 'yu',  'Я' => 'ya', 'a' => 'a', 'A' => 'a', 'o' => 'o', 'O' => 'o', 'u' => 'u', 'U' => 'u', 'o' => 'o', 'O' => 'O', '>' => '', '<' => '', '=' => '', '-' => '', '+' => '', '!' => '', ' ' => '-', '  ' => '_', '!' => '', '@' => '', '#' => '', '$' => '', '%' => '', '^' => '', '&' => '','*' => '','(' => '',')' => '', '|'=> '', '/' => '', 'ь' => '',
        'Ь' => '', 'ъ' => '', 'Ъ' => '', '.' => '', ',' => '');
        return strtr($string, $array);

    }
    
    public function import($data)
    {
        for ($i = 0; $i < count($data['name']); $i++) 
        {
            try        
            {
                ORM::factory('city')
                    ->set('regions_id', $data['regions_id'])
                    ->set('districts_id', 1)
                    ->set('name', $data['name'][$i])
                    ->set('name_rp', $data['name_rp'][$i])
                    ->set('name_pp', $data['name_pp'][$i])
                    ->set('domain', $data['domain'][$i])
                    ->set('longitude', $data['latitude'][$i])
                    ->set('latitude', $data['latitude'][$i])
                    ->set('districts_id', 1)
                    ->create();
            }   
            catch (ORM_Validation_Exception $ex)         
            {
                return false;
            }
        }
        return true;
    }
}