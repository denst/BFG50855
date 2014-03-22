<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Dist extends ORM {

    public $_add = array('fields' => array('url', 'name', 'countries_id', 'title','keywords','desc','h1','main','footer'));

	protected $_table_name = 'districts_desc';
    private $replace = FALSE;
    private $area;
    

    public function setReplace($state, $area = null) {
        $this->replace = $state;
        $this->area = $area;
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
            $column = str_replace(array('{dist}','{dist_rp}'), array($this->area->name,$this->area->rp_name), $column);
        }

        return $column;
    }

}