<?

class Controller_Regionslist extends Template {

    function action_index() {

        $this->request->redirect('/');

        $view = View::factory('pages/regions/list_client');
        $view->countries = ORM::factory('country')->where('id','=',1)->find_all();

        $this->template->content = $view;
        $this->template->title = 'Регионы';

    }

}

?>
