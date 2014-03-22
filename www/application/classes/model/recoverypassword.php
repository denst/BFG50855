<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_RecoveryPassword extends ORM {
    
    protected $_table_name = 'recovery_password';
    
    private $user_id;
    private $errors;
    
    protected $_belongs_to = array(
        'user' => array(
            'model' => 'user',
        )
    );

    public function write_temp_link($user, $temp_link)
    {
        try 
        {
            ORM::factory('recoverypassword')
                ->set('user_id', $user->id)
                ->set('temp_link', $temp_link)
                ->set('status', 'send')
                ->set('date', date("Y-m-d H:m:s"))
                ->save();
           return true;
        }
        catch (Exception $exc) 
        {
            return false;
        }
    }
    
    public function check_link($temp_link)
    {
        $temp_link = strip_tags($temp_link);
        
        $recoverypassword = ORM::factory('recoverypassword')
            ->where('temp_link', '=', $temp_link)
            ->find();
        
        if($recoverypassword->loaded())
        {
            $recovery_date_plus_1_hour = strtotime('+1 hours', strtotime($recoverypassword->date));
            if(time() > $recovery_date_plus_1_hour)
            {
                $this->errors = 'Срок действия ссылки истёк';
                return false;                
            }
            elseif($recoverypassword->status == 'used')
            {
                $this->errors = 'Данная ссылка уже использована';
                return false;
            }
            $recoverypassword->set('status', 'used')->update();
            $this->user_id = $recoverypassword->user->id;
            
            return true;
        }
        else
        {
            $this->errors = 'Неверная ссылка';
            return false;
        }
    }
    
    public function get_user_id()
    {
        return $this->user_id;
    }
    
    public function get_errors()
    {
        return $this->errors;
    }
}
