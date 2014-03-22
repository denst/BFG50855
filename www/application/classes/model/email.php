<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Email {
    
    /**
     * Формирование письма на восстановление пароля
     *
     * @param   mixed   $user
     * @param   string  $temp_link
     * @return  bool  
    */     
    public function recoverypassword($user, $temp_link)
    {
        $link_for_reset_password = URL::base('http').'auth/newpassword/'.$temp_link;
        $subject = 'Восстановление пароля для '.URL::base('http');
        
        $recovery_email = Settings::instance()->get_setting('recoverypassword_email');
        $res1 = str_replace('{user_name}', Text::ucfirst($user->username), $recovery_email);
        $res2 = str_replace('{site_name}', 'http://'.$_SERVER['HTTP_HOST'], $res1);
        $message = str_replace('{recovery_link}', 
            '<a href="'.$link_for_reset_password.'">'.$link_for_reset_password.'</a>', $res2);
        return $this->email_body($user->email, $subject, $message);
    }
    
   /**
     * Отправляем сгенерированные письма
     *
     * @param   string  $to
     * @param   string  $subject
     * @param   string  $message
     * @return  bool  
    */     
    private function email_body($to, $subject, $message)
    {
        $config = Kohana::$config->load('email');
        Email::connect($config);
        $from = Settings::instance()->get_setting('admin_email');

        if(($res = Email::send($to, $from, $subject, $message, $html = true)))
        {
            return true;
        }
        else
            return false;        
    }
}