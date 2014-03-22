<?php

class Controller_Auth extends Template
{

	function action_index()
	{
		$view = View::factory('pages/auth/login');
                
                if(Valid::not_empty(Session::instance()->get('error')))
                    $view->set('error_message', Session::instance()->get_once('error'));
                
		$this->template->content = $view;
	}

	public function action_login()
	{
		if (Security::check_token() || true)
		{
			$login = Arr::get($_POST, 'login');
			$pass = Arr::get($_POST, 'password');

			$success = Auth::instance()->login($login,$pass,true);

			if ($success)
			{
                $user = ORM::factory('user',array('email'=>$login));

                if ($user->has('roles',ROLE_ADMIN))
                    $this->SendJSONData(array(
                        JSONA_RELOADPAGE => '/users'
                    ));
                else
                    $this->SendJSONData(array(
                        JSONA_RELOADPAGE => '/posts/my'
                    ));

			} else {
				$this->SendJSONData(array(
					JSONA_ERROR => 'Неверный логин или пароль',
				));
			}
		} else {
			$this->SendJSONData(array(
				JSONA_ERROR => 'Неверный ключ безопасности',
			));
		}
	}

	public function action_logout()
	{
		$this->auth->logout();
                $this->request->redirect('/auth');
	}

    public function action_register()
    {

        if ($this->isJson()) {

            if (Security::check_token())
            {
                $user = ORM::factory('user', array('email' => Arr::get($_POST,'email')));

                if ($user->loaded()) {

                    $this->SendJSONData(array(
                        JSONA_ERROR => 'Пользователь с таким Email\'ом уже существует в нашей системе.'
                    ));

                } else {

                    $user->username = Arr::get($_POST,'login');
                    $user->password = Arr::get($_POST,'password');
                    $user->email = Arr::get($_POST,'email');
                    $user->phone = '+7 ' . Arr::get($_POST,'phone_code') . ' ' . Arr::get($_POST,'phone_number');
                    $user->cities_id = Arr::get($_POST,'city_id');
                    $user->save();
                    $user->add('roles',ROLE_LOGIN);

                    $this->SendJSONData(array(
                        JSONA_COMPLETED => 'Пользователь успешно зарегистрирован',
                        JSONA_REDIRECT => '/auth',
                    ));

                }

            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Неверный ключ безопасности',
                ));
            }

        } else {

            $view = View::factory('pages/auth/register');

            $this->template->content = $view;
            $this->template->title = 'Регистрация пользователя';

        }

    }
    
    public function action_recoverypassword()
    {

        if ($this->isJson()) {

            if (Security::check_token())
            {
                $email = strip_tags(Arr::get($_POST,'email'));
                $user = ORM::factory('user', array('email' => $email));

                if (! $user->loaded()) {

                    $this->SendJSONData(array(
                        JSONA_ERROR => 'Пользователя с таким Email\'ом нет в нашей системе.'
                    ));

                } else {

                    $temp_link = Text::random('alnum', 50);
                    Model::factory('recoverypassword')->write_temp_link($user, $temp_link);
                    if(Model::factory('email')->recoverypassword($user, $temp_link))
                    {
                        $this->SendJSONData(array(
                            JSONA_COMPLETED => 'На указанный вами Email
                                было выслано письмо, с инструкцией по восстановлению пароля.',
                        ));
                    }
                    else
                        $this->SendJSONData(array(
                            JSONA_ERROR => 'По техническим причинам на ваш Email не может быть выслано письмо, с инструкцией по восстановлению пароля'
                        ));
                }

            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Неверный ключ безопасности',
                ));
            }

        } else {

            $view = View::factory('pages/auth/recoverypassword');

            $this->template->content = $view;
            $this->template->title = 'Восстановления пароля';

        }

    }
    
    public function action_newpassword() 
    {   
        if ($this->isJson()) {

            if (Security::check_token())
            {
                $pass = Arr::get($_POST,'password');
                $retpass = Arr::get($_POST,'password_retry');
                $user_id = Arr::get($_POST,'user_id');

                if ($pass === $retpass)
                {
                    $user = ORM::factory('user', $user_id);
                    $user->password = $pass;
                    $user->save();

                    $this->SendJSONData(array(
                        JSONA_COMPLETED => 'Пароль успешно изменен',
                        JSONA_REDIRECT => '/auth',
                    ));
                } else {
                    $this->SendJSONData(array(
                        JSONA_ERROR => 'Введенные пароли не совпадают'
                    ));
                }

            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Неверный ключ безопасности',
                ));
            }

        } else {

            $model_recoverypassword = Model::factory('recoverypassword');
            if($model_recoverypassword->check_link($this->request->param('id')))
            {
                $view = View::factory('pages/auth/newpassword');
                $view->user_id = $model_recoverypassword->get_user_id();

                $this->template->content = $view;
                $this->template->title = 'Новый пароля';
                
            }
            else
            {
                Session::instance()->set('error', $model_recoverypassword->get_errors());
                $this->request->redirect('/auth');
            }
        }
    }
    
    function action_updateparams() {

        $this->checkLogin();

        if ($this->isJson()) {

            if (Security::check_token()) {

                $this->user->username = Arr::get($_POST,'username');
                $this->user->email = Arr::get($_POST,'email');
                $this->user->cities_id = Arr::get($_POST,'cities_id');
                $this->user->phone = Arr::get($_POST,'phone');
                $this->user->save();

                $this->SendJSONData(array(
                    JSONA_COMPLETED => 'Данные успешно обновлены',
                    JSONA_REFRESHPAGE => ''
                ));

            } else {
                $this->SendJSONData(array(
                    JSONA_ERROR => 'Неверный ключ безопасности. Пожалуйста, попробуйте еще раз',
                    JSONA_RELOADPAGE => ''
                ));
            }

        }

    }
    
    // Настройки пользователя
    function action_settings() 
    {
        
        $this->checkLogin();

        $view = View::factory('pages/auth/settings');

        $this->template->content = $view;
        $this->template->title = 'Мои объявления';

    }

}

?>
