<?

class Controller_Users extends Main {

    public $template = 'admin';
    protected $document_template = 'admin';

	function before()
	{
		parent::before();
		$this->checkAdminLogin();
	}

    protected $_model_name = 'user';
    protected $_add = array(
        'username' => array(
            'tag' => 'input',
            'label' => 'Логин',
            'attributes' => array(),
        ),
        'password' => array(
            'tag' => 'input',
            'label' => 'Пароль',
            'attributes' => array('data-validation' => 'notempty;minlength:6'),
        ),
        'email' => array(
            'tag' => 'input',
            'label' => 'E-Mail',
            'attributes' => array('data-validation' => 'notempty;isemail'),
        ),
        'cities_id' => array(
            'tag' => 'select',
            'label' => 'Город',
            'model' => 'city',
            'model_key' => 'id',
            'model_value' => 'name',
            'attributes' => array('data-validation' => 'notempty'),
        ),
        'phone' => array(
            'tag' => 'input',
            'label' => 'Телефон',
            'attributes' => array('data-validation' => 'isphone'),
        ),
        'icq' => array(
            'tag' => 'input',
            'label' => 'ICQ',
            'attributes' => array('data-validation' => ''),
        ),
    );

    protected $_edit = array(
        'username' => array(
            'tag' => 'input',
            'label' => 'Логин',
            'attributes' => array(),
        ),
        'password' => array(
            'tag' => 'input',
            'label' => 'Пароль',
            'attributes' => array('data-validation' => 'notempty;minlength:6'),
        ),
        'email' => array(
            'tag' => 'input',
            'label' => 'E-Mail',
            'attributes' => array('data-validation' => 'notempty;isemail'),
        ),
        'cities_id' => array(
            'tag' => 'select',
            'label' => 'Город',
            'model' => 'city',
            'model_key' => 'id',
            'model_value' => 'name',
            'attributes' => array('data-validation' => 'notempty'),
        ),
        'phone' => array(
            'tag' => 'input',
            'label' => 'Телефон',
            'attributes' => array('data-validation' => 'isphone'),
        ),
        'icq' => array(
            'tag' => 'input',
            'label' => 'ICQ',
            'attributes' => array('data-validation' => ''),
        ),
    );

    protected $_messages = array(
        'add' => array(
            'success' => 'Пользователь создан',
            'error' => 'Ошибка создания пользователя',
        ),
        'edit' => array(
            'success' => 'Пользователь отредактрован',
            'error' => 'Ошибка редактрования пользователя',
        ),
        'delete' => array(
            'success' => 'Пользователь удален',
            'error' => 'Ошибка удаления пользователя',
        ),
    );

    /**
     * Возвратить массив строк контроллера
     */
    protected function get_params($model) {
        return array(
            'add' => array(
                'caption' => 'Создание нового пользователя',
                'legend' => '',
                'button' => 'Создать',
            ),
            'edit' => array(
                'caption' => 'Редактирование пользователя "' . (string)$model . '"',
                'legend' => '',
                'button' => 'Применить изменения',
            ),
            'delete' => array(
                'caption' => 'Удаление пользователя "' . (string)$model . '"',
                'answer' => 'Вы в курсе, что вы удаляете пользователя "' . (string)$model . '"?',
                'button' => 'Удалить пользователя',
            )
        );
    }

	function action_add()
	{
		if (!empty($_POST['username']))
		{
			$user = $this->add();
    		if ($user !== FALSE)
			{
				$user->add('roles',ROLE_LOGIN);
			}
		} else {
			$this->template->content = $this->form_add();
		}
	}

	function action_edit()
	{
		if (!empty($_POST['username']))
		{
			$this->edit($this->request->param('id'));
		} else {
			$this->template->content = $this->form_edit($this->request->param('id'));
		}
	}

	function action_index()
	{
        $users = ORM::factory('user');

		$view = View::factory('pages/users/list');
		$view->users = $users->pagination();
		$view->pager = $users->pagination_html();

		$this->template->content = $view;
		$this->template->title = 'Управление пользователями';
	}

	function action_toggle()
	{
		$user = ORM::factory('user',$this->request->param('id'));

		if ($user->loaded() && $this->user->has('roles',ROLE_ADMIN) && Security::check_token())
		{
			if ($user->has('roles',ROLE_ADMIN))
			{
				if ($user->id !== $this->user->id)
				{
                    $user->remove('roles',ROLE_ADMIN);

                    $this->SendJSONData(array(
                        JSONA_COMPLETED => (string)$user . ' стал простым смертным',
                        JSONA_REFRESHPAGE => '',
                    ));
				} else {
					$this->SendJSONData(array(
						JSONA_ERROR => 'Зачем самому себе админа убирать?)',
					));
				}
			} else {
				$user->add('roles',ROLE_ADMIN);

				$this->SendJSONData(array(
					JSONA_COMPLETED => (string)$user . ' стал админом',
					JSONA_REFRESHPAGE => '',
				));
			}
		} else {
			$this->SendJSONData(array(
				JSONA_ERROR => 'Недостаточно прав',
			));
		}
	}

	function action_delete()
	{
		$id = $this->request->param('id');
		$user = ORM::factory('user',$id);

		if (!isset($_REQUEST[TOKEN]))
		{
			$this->template->content = $this->form_delete($id);
		} else {
			if ($user->loaded() && $this->user->has('roles',ROLE_ADMIN) && Security::check_token())
			{
				if ($user->id !== $this->user->id)
				{
					if ($user->username == 'saggid')
					{
						$this->SendJSONData(array(
							JSONA_COMPLETED => 'И кто же тогда будет сайт делать? :)',
							JSONA_REFRESHPAGE => '',
						));
					} else {
						$user->remove('roles',ROLE_LOGIN);
						$user->save();

						$this->SendJSONData(array(
							JSONA_COMPLETED => $user . ' удален из системы',
							JSONA_REFRESHPAGE => '',
						));
					}
				} else {
					$this->SendJSONData(array(
						JSONA_ERROR => 'Удалять самого себя?',
					));
				}
			} else {
				$this->SendJSONData(array(
					JSONA_ERROR => 'Недостаточно прав',
				));
			}
		}
	}

    function action_password()
    {
        $user = ORM::factory('user',$this->request->param('id'));

        if ($user->loaded())
        {
            if (Security::check_token())
            {
                $pass = Arr::get($_POST,'pass');
                $retpass = Arr::get($_POST,'retpass');

                if ($pass === $retpass)
                {
                    $user->password = $pass;
                    $user->save();

                    $this->SendJSONData(array(
                        JSONA_COMPLETED => 'Пароль успешно изменен',
                        JSONA_REFRESHPAGE => ''
                    ));
                } else {
                    $this->SendJSONData(array(
                        JSONA_ERROR => 'Введенные пароли не совпадают'
                    ));
                }

            } else {
                $view = View::factory('pages/users/password');
                $view->user = $user;

                $this->template->content = $view;
                $this->template->title = 'Изменени пароля пользователя ' . $user;
            }
        } else {
            throw new HTTP_Exception_404;
        }

    }

}

?>
