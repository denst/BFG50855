<?

// Класс управления новостными блогами
class Blog extends Template {

    function action_index()
    {
        $blogs = ORM::factory('blog');

        if (!$this->auth->logged_in())
            $blogs->where('show','=','1');

        $blogs->where('blog_types_id','=',$this->type_id)->order_by('date','desc');

        $view = View::factory('pages/blog/list');
        $view->blogs = $blogs->pagination(10);
        $view->pager = $blogs->pagination_html(10);
        $view->blog_name = $this->type_name;
        $view->main_link = $this->main_link;
        $view->item_link = $this->item_link;

        $this->template->content = $view;
        $this->template->title = $this->type_name;
    }

    function action_show()
    {
        $blog = ORM::factory('blog',$this->request->param('id'));

        if (!$blog->loaded())
            throw new HTTP_Exception_404;

        $view = View::factory('pages/blog/show');
        $view->blog = $blog;
        $view->blog_name = $this->type_name;
        $view->main_link = $this->main_link;

        $this->template->content = $view;
        $this->template->title = $blog->name;
        $this->template->desc = $blog->text;
        $this->template->keywords = $blog->keywords;
    }

    function action_save()
    {
        $this->checkAdminLogin();

        $blog = ORM::factory('blog',$this->request->param('id'));

        $edit = FALSE;
        if ($blog->loaded())
            $edit = TRUE;

        if ($this->isJson())
        {
            if (Security::check_token())
            {
                $blog->name = Arr::get($_POST,'name');
                $blog->blog_types_id = $this->type_id;
                $blog->date = strtotime(Arr::get($_POST,'date'));
                $blog->text = Arr::get($_POST,'text');
                $blog->desc = Arr::get($_POST,'desc');
                $blog->keywords = Arr::get($_POST,'keywords');
                $blog->show = isset($_POST['show']) ? true : false;
                $blog->photo_id = Arr::get_empty($_POST,'photo_id');
                $blog->save();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => $edit ? 'Запись обновлена' : 'Запись добавлена',
                    JSONA_REDIRECT => $this->item_link . $blog->id,
                ));
            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Неверный ключ безопасности. Пожалуйста, обновите страницу.'
                ));
            }
        } else {
            $view = View::factory('pages/blog/add_edit');
            $view->blog = $blog;

            $this->template->content = $view;
            $this->template->title = $edit ? 'Редактирование записи' : 'Создание записи';
        }
    }

    function action_delete()
    {
        $this->checkAdminLogin();

        $blog = ORM::factory('blog',$this->request->param('id'));

        if (!$blog->loaded())
            throw new HTTP_Exception_404;

        if (Security::check_token())
        {
            $blog->delete();

            $this->SendJSONData(array(
                JSONA_COMPLETED => 'Запись удалена',
                JSONA_REDIRECT => $this->main_link
            ));

        } else {
            $this->SendJSONData(array(
                JSONA_ERROR => 'Неверный ключ безопасности. Пожалуйста, обновите страницу.'
            ));
        }

    }

    function action_toggleshow()
    {
        $this->checkAdminLogin();

        $blog = ORM::factory('blog',$this->request->param('id'));

        if (!$blog->loaded())
            throw new HTTP_Exception_404;

        if (Security::check_token())
        {
            if ($blog->show)
                $blog->show = FALSE;
            else
                $blog->show = TRUE;

            $blog->save();

            $this->SendJSONData(array(
                JSONA_REFRESHPAGE => ''
            ));
        } else {
            $this->SendJSONData(array(
                JSONA_ERROR => 'Неверный ключ безопасности. Пожалуйста, обновите страницу.'
            ));
        }
    }


}

?>
