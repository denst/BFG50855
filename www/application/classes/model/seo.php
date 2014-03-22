<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Seo extends ORM {

    public $_add = array('fields' => array('url','name', 'countries_id', 'title','keywords','desc','h1','main','footer'));

	protected $_table_name = 'seo_pages';
    private $replace = FALSE;

    public function setReplace($state) {
        $this->replace = $state;
    }

    protected $_belongs_to = array(
		'country' => array(
            'model' => 'country',
            'foreign_key' => 'countries_id',
        ),
    );

    // Выводим зафильтрованные данные
    public function __get($column)
    {
        $column = parent::__get($column);

        if ($this->replace) {
            $location = View::get_global('location');
            $column = str_replace(array('{name}','{name_rp}','{name_pp}'), array($location->name,$location->name_rp,$location->name_pp), $column);
        }

        return $column;
    }

}