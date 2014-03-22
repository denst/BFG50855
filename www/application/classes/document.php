<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Assign document control
 *
 *
 * @package    System
 * @category   Helpers
 * @author     Alexey Yu. Shevyakov
 * @copyright  (c) 2011 Aliance Team :)
 */
class Document
{

	// Document instances
	protected static $_instances = array();

	/**
	 * Singleton pattern
	 *
	 * @return Document
	 */
	public static function instance($name)
	{
		if (!isset(Document::$_instances[$name]))
		{
			// Create a new session instance
			Document::$_instances[$name] = new Document($name);
		}

		return Document::$_instances[$name];
	}

	protected $_scripts;
	protected $_styles;
	protected $_title;
	protected $_custom;
	protected $_theme;
	protected $_h1;
    protected $_name;

    public function __construct($name)
	{
        $this->_name = $name;
        $this->clear();
	}

    public function clear()
    {
		$this->_scripts = array();
		$this->_styles = array();
		$this->_title = '';
		$this->_custom = array();
		$this->_h1 = '';
    }

	/**
	 * Adds a linked script to the page
	 *
	 * @param	string  $url		URL to the linked script
	 * @access   public
	 */
	public function addScript($url)
	{
		if (!in_array($url, $this->_scripts))
			array_push($this->_scripts, $url);
	}

	/**
	 * Adds a linked stylesheet to the page
	 *
	 * @param	string  $url	URL to the linked style sheet
	 * @access   public
	 */
	public function addStyleSheet($url, $media = "screen")
	{
		$this->_styles[$url] = $media;
	}

	public function addCustom($string)
	{
		if (!in_array($string, $this->_custom))
			array_push($this->_custom, $string);
	}

	public function getScripts()
	{
		return $this->_scripts;
	}

	public function getStyleSheets()
	{
		return $this->_styles;
	}

	public function getCustom()
	{
		return $this->_custom;
	}

	public function setScripts($scripts)
	{
		$this->_scripts = $scripts;
	}

	public function setStyleSheets($styles)
	{
		$this->_styles = $styles;
	}

	public function setCustom($customs)
	{
		$this->_custom = $customs;
	}

	public function setTheme($theme)
	{
		$this->_theme = $theme;
	}

	public function setH1($h1)
	{
		$this->_h1 = $h1;
	}

	public function getTheme()
	{
		return $this->_theme;
	}

	public function getH1()
	{
		return $this->_h1;
	}

	public function render()
	{
		$header = array(
			'scripts' => $this->_scripts,
			'styles' => $this->_styles,
			'custom' => $this->_custom,
            'name' => $this->_name,
		);

		$this->header = View::factory('header', $header);
		return $this->header;
	}

    static function checkFilesExists()
    {
        $templates = self::$_instances;

        foreach($templates as $template)
        {
            if (!file_exists(DOCROOT.'themes/packed/css/'.$template->name().'.css'))
                return false;

            if (!file_exists(DOCROOT.'themes/packed/js/'.$template->name().'.js'))
                return false;
        }

        return true;
    }

    public static function compile($template)
    {
        if (!isset(self::$_instances[$template]))
            return null;

        $document = self::$_instances[$template];

        if (Kohana::$environment == Kohana::PRODUCTION && false)
        {
            if ((Request::initial()->controller() == 'admin' AND Request::initial()->action() == 'recompress')
                OR (!self::checkFilesExists()))
            {
                $documents = self::$_instances;
                foreach($documents as $doc)
                    $doc->recompress();
            }

            $view = View::factory('header');
            $view->scripts = array('../packed/js/' . $template . '.js?' . filemtime(DOCROOT.'/themes/packed/js/'.$template.'.js'));
            $view->styles = array('packed/css/' . $template . '.css?' . filemtime(DOCROOT.'/themes/packed/css/'.$template.'.css') => 'all');
            $view->custom = $document->getCustom();

            return $view;
        } else {
            return $document->render();
        }
    }

    // Производит сжатие всех файлов указанного шаблона в один файл скриптов и один файл стилей
    function recompress()
    {
        $csspath = DOCROOT.'themes/packed/css/'.$this->_name.'.css';
        $jspath = DOCROOT.'themes/packed/js/'.$this->_name.'.js';

        if (file_exists($jspath))   unlink($jspath);
        if (file_exists($csspath))  unlink($csspath);

        $asset = Assets::factory($this->_name);

        foreach ($this->_styles as $file => $type)
            $asset->css($file);

        foreach ($this->_scripts as $file)
            $asset->js($file);

        $asset->render();
    }

	/**
	 * Подключить jQuery-плагин Chosen
	 */
	public function joinChosen()
	{
		$this->addScript("chosen/chosen.jquery.js");
		$this->addStyleSheet("js/chosen/chosen.css");
    }

    public function joinRedactor()
    {
		$this->addScript('redactor/redactor.js');
		$this->addStyleSheet('js/redactor/redactor.css');
    }

    public function name()
    {
        return $this->_name;
    }

}

