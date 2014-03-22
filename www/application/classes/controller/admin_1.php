<?php

class Controller_Admin extends Template
{

    public $template = 'admin';
    protected $document_template = 'admin';

	function action_index()
	{
        $this->checkAdminLogin();
		$view = View::factory('pages/admin/index');

		$this->template->content = $view;
		$this->template->title = 'Логово админа';
	}

	function action_recompress()
	{
		$this->template->content = 'recompress completed';
	}

}

?>
