<?
class Controller_Page extends Template {

    public $template = 'admin';
    protected $document_template = 'admin';

    function action_show()
    {
        $page = ORM::factory('page',array('lat_name' => $this->request->param('page')));

        if (!$page->loaded())
            throw new HTTP_Exception_404;

        $view = View::factory('pages/page/show');
        $view->page = $page;

        $this->template->content = $view;
        $this->template->title = $page->name;
        $this->template->desc = $page->text;
    }

	public function action_admin()
	{
		$this->checkAdminLogin();

        $view = View::factory('pages/page/list');
        $view->pages = ORM::factory('page')->find_all();

		$this->template->content = $view;
		$this->template->title = 'Страницы';
	}

    function action_save()
    {
        $this->checkAdminLogin();

        $page = ORM::factory('page',$this->request->param('id'));

        $edit = FALSE;
        if ($page->loaded())
            $edit = TRUE;

        if ($this->isJson())
        {
            if (Security::check_token()) {
                $page->name = Arr::get($_POST,'name');
                $page->lat_name = Text::RusToLat(Arr::get($_POST,'lat_name'));
                $page->text = Arr::get($_POST,'text');
                $page->keywords = Arr::get($_POST,'keywords');
                $page->save();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => $edit ? 'Страница обновлена' : 'Страница создана',
                    JSONA_REDIRECT => '/page/admin',
                ));
            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Ошибка секретного ключа. Пожалуйста, обновите страницу и повторите попытку'
                ));
            }
        } else {
            $view = View::factory('pages/page/add_edit');
            $view->page = $page;

            $this->template->content = $view;
            $this->template->title = $page->name;
        }
    }

    function action_delete()
    {
        $this->checkAdminLogin();

        $page = ORM::factory('page',$this->request->param('id'));

        if (!$page->loaded())
            throw new HTTP_Exception_404;

        $page->delete();

        $this->request->redirect('/page/admin');

    }
}
?>
