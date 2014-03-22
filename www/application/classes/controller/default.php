<?php

class Controller_Default extends Template
{

	function action_index()
	{
		$view = View::factory('pages/default/index');

		$this->template->content = $view;
		$this->template->title = 'Главная';
	}

}

?>
