<?

class Controller_Posts_Flats extends main {

    public $template = 'admin';
    protected $document_template = 'admin';

    function before() {
        parent::before();
        $this->checkLogin();
    }

    function action_index() {
        $this->checkAdminLogin();
        $flats = ORM::factory('adverts_types_flat');

        $view = View::factory('pages/posts/flats/admin');
        $view->flats = $flats->pagination(50);
        $view->flats_pager = $flats->pagination_html(50);

        $this->template->content = $view;
        $this->template->title = 'Управление квартирами';
    }




}

?>
