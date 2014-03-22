<?
class Controller_News extends Blog {

    public $template = 'admin';
	public static $ajax_generated = FALSE;
    protected $document_template = 'admin';

    protected $type_id = 1;
    protected $type_name = 'Новости';
    protected $item_link = '/news/show/';
    protected $main_link = '/news';
	
    function action_getlast()
    {
		$view = View::factory('columns/news');
		$this->auto_render = false;
		$this->response->body($view);
    }

}
?>
