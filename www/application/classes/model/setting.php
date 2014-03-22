<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Setting extends ORM {
    
    private $fields = array('empty_areas', 'admin_email',
        'form_registration', 'form_registration_email',
        'form_authorization', 'recoverypassword_step1',
        'recoverypassword_step2', 'recoverypassword_email');
    
    public function set_settings($settings)
    {
        if(!isset($settings['empty_areas']))
            $settings['empty_areas'] = 'off';
        
        Model::factory('price')->update_course($settings['courses']);
        Model::factory('country')->update_currency($settings);
        
        try
        {
            foreach ($this->fields as $field) 
            {
                $setting = ORM::factory('setting')->where('key', '=', $field)->find(); 
                $setting->set('value', $settings[$field])->update();
            }
            return true;
        }
        catch (ORM_Validation_Exception $e)
        {
            return false;
        }
    }
    
    public static function get_settings()
    {
        $settings = array();
        
        $all_settings = ORM::factory('setting')->find_all();
        foreach ($all_settings as $setting)
        {
            $settings[$setting->key] = $setting->value;
        }
        return $settings;
    }
}