<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

    public $_add = array('fields' => array('username','password', 'email','cities_id','phone','icq'));
    public $_edit = array('fields' => array('username','password', 'email','cities_id','phone','icq'));

	public function __toString() {
		if ($this->loaded())
			return $this->username;
		else
			return '';
	}

	protected $_belongs_to = array(
		'city' => array(
            'model' => 'city',
            'foreign_key' => 'cities_id',
        ),
    );

} // End User Model