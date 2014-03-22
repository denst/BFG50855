<?

class Model_Blog extends ORM {

	protected $_table_name = 'blog';

	protected $_belongs_to = array(
		'user' => array(
            'model' => 'user',
            'foreign_key' => 'users_id',
        ),
		'photo' => array(
            'model' => 'file',
            'foreign_key' => 'photo_id',
        ),
    );

    public function link()
    {
        return '/blogs/' . $this->id;
    }

    public function __toString() {
        return HTML::anchor($this->link(),$this->name);
    }


}

?>
