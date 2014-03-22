<?php

class Controller_Auth extends Template
{

	function action_index()
	{
		$view = View::factory('pages/auth/login');
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
                        JSONA_RELOADPAGE => '/admin'
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
		$this->SendJSONData(array(
			JSONA_RELOADPAGE => '/',
		));
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

    function action_updateparams() {

        $this->checkLogin();

        if ($this->isJson()) {

            if (Security::check_token()) {

                $this->user->username = Arr::get($_POST,'username');
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

}

?>
