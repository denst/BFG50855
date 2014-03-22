<?php

class Controller_Message extends Template
{
	protected $needfulltemplate = true;

	function after()
	{
		if (Request::initial()->is_ajax())
			$this->needfulltemplate = false;

		parent::after();
	}

	function action_error()
	{
		$code = $this->request->param('id');

		if (file_exists(APPPATH.'views/errors/'.$code.EXT))
		{
			$this->template->title = 'Ошибка № '.$code;
			$content = View::factory('errors/'.$code);
		} else {
			$this->template->title = 'Неизвестная ошибка';
			$content = View::factory('errors/unknown');
		}

        if ($this->isJson())
        {
            $this->SendJSONData(array(
                JSONA_ERROR => $content->render()
            ));
        } else {
            $this->template->content = $content;
        }


	}
}

?>
