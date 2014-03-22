<?php

class Controller_Admin extends Template
{

    public $template = 'admin';
    protected $document_template = 'admin';

	function action_index()
	{
                $this->checkAdminLogin();
		$view = View::factory('pages/admin/index');
                $view->currencies = ORM::factory('price')->find_all();
                $view->countries = ORM::factory('country')->find_all()->as_array();

		$this->template->content = $view;
		$this->template->title = 'Логово админа';
	}

	function action_recompress()
	{
		$this->template->content = 'recompress completed';
	}
        
        function action_settings()
        {
            $this->checkAdminLogin();

            if ($this->isJson()) {

                if (Security::check_token()) {

                    if(Model::factory('setting')->set_settings($_POST))
                    {
                        $this->SendJSONData(array(
                            JSONA_COMPLETED => 'Данные успешно обновлены',
                        ));
                    }

                } else {
                    $this->SendJSONData(array(
                        JSONA_ERROR => 'Неверный ключ безопасности. Пожалуйста, попробуйте еще раз',
                        JSONA_RELOADPAGE => ''
                    ));
                }

            }
        }

}

?>
