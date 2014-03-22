<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Ad extends ORM {

    public $_add = array('fields' => array('name', 'countries_id', 'position_id', 'code'));

    protected $_table_name = 'ads';
    
    protected $_belongs_to = array(
        'country' => array(
            'model' => 'country',
            'foreign_key' => 'countries_id',
        ),
        'position' => array(
            'model' => 'adposition',
            'foreign_key' => 'position_id',
        ),
    );
}