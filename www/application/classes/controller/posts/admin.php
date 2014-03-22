<? class Controller_Posts_Admin extends main {
    public $template = 'admin';
    protected $document_template = 'admin';

    function before() {
        parent::before();
        $this->checkAdminLogin();
    }

    function action_index() {
        $adverts = ORM::factory('advert')->order_by('id','desc');

        $view = View::factory('pages/posts/admin');
        $view->adverts = $adverts->pagination(50);
        $view->pager = $adverts->pagination_html(50);

        $this->template->content = $view;
        $this->template->title = 'Управление объявлениями';
    }
    
    function action_deleteitems() {
        if (Security::check_token()) {
            
            $adverts = Arr::get($_POST,'adverts');
            foreach($adverts as $advert_id) {
                $advert = ORM::factory('advert',$advert_id);
                if ($advert->loaded()) {
                    $advert->fullDelete();
                }
            }
            
            $this->SendJSONData(array(
                JSONA_COMPLETED => 'Объявления успешно удалены',
                JSONA_REFRESHPAGE => '',
            ));
        }
    }
}
