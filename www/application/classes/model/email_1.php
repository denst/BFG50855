<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Email {
    
    public function shipment_email($email, $message)
    {
        $subject = "Shipment completed";
        if($this->send($email, $subject, $message))
        {
            $this->logs('shipment_email');
            return true;
        }
        else
            return false;
    }
    
    public function warning_email($email)
    {
        $to = $email;
        $subject = "notifications massages from ".URL::base('http');
        $message = '<div>You are very close to reach your next $20, please add funds to 
                avoid button pausing</div>';
        if($this->send($to, $subject, $message))
        {
            $this->logs('warning email');
            return true;
        }
        else
            return false;
    }
    
    public function block_email($email)
    {
        $to = $email;
        $subject = "Blocked account ".URL::base('http');
        $message = '<div>Your account on '.URL::base('http').' paused, 
            please add funds to turn to live status!</div>';
        if($this->send($to, $subject, $message))
        {
            $this->logs('block email');
            return true;
        }
        else
            return false;
    }
    
    public function thankyou_email($user_id, $email)
    {
        $user = Model::factory('user')->get_user_by_id($user_id);
        $to = $email;
        $subject = "success payment on ".URL::base('http');
        $message = '<div>Thanks for your support '.$user->username.'!</div>';
        if($this->send($to, $subject, $message))
        {
            $this->logs('thankyou email');
            return true;
        }
        else
            return false;
    }
    
    public function success_registration($user, $password)
    {
        $subject = "Registration on ".URL::base('http');
        $message = '<p>Thank you that you have chosen to use '.URL::base('http').'</p>'.
                   '<p>Registration was successful!</p>
                    <p>Your username : '.$user->username.'</p>
                    <p>sellwy team</p>';
        if($this->send($user->email, $subject, $message))
        {
            $this->logs('succes email');
            return true;
        }
        else
            return false;        
    }
    
    public function reset_password($email, $temp_link)
    {
        $link_for_reset_password = URL::base('http').'resetpassword/newpassword/'.$temp_link;
        $user = Model::factory('user')->get_user_by_field_value('email', $email);
        $to = $email;
        $subject = 'Reset password for '.URL::base('http');
        $message = 
            '<p>Hello '.Text::ucfirst($user->username).', you sent a request for an account reset password on '.URL::base('http').'.</p>'. 
            '<p>Please click or copy this link <a href="'.$link_for_reset_password.'">'.$link_for_reset_password.'</a> '.
            'to set a new password.<p>Thank you for using '.URL::base('http').'</p>';
        if($this->send($user->email, $subject, $message))
        {
            $this->logs('reset password email');
            return true;
        }
        else
            return false; 
            
    }

    public function send($to, $subject, $message)
    {
        $config = Kohana::$config->load('email');
        Email::connect($config);
        $from = Settings::instance()->get_setting('site_email');
        $message = 
            '<html>'.
                '<head>'.
                    '<title>sellwy</title>'.
                '</head>'.
                '<body>'.
                    nl2br($message).
                '</body>'.
            '</html>';
//        $from = 'Support '.'<'.$from.'>';
        $res = Email::send($to, $from, $subject, $message, $html = true);
        if($res > 0)
            return true;
        else
            return false;
    }
    
    private function logs($name)
    {
        ini_set('log_errors', true);
        ini_set('error_log', APPPATH.'classes/errors/email_errors.log');
        error_log("--------------------------------------");
        error_log('send '.$name);
    }
}