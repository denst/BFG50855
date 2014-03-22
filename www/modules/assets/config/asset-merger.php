<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'merge'      => Kohana::PRODUCTION,
	'folder'     => 'themes/packed',
	'load_paths' => array(
		Assets::JAVASCRIPT => DOCROOT.'themes/js'.DIRECTORY_SEPARATOR,
		Assets::STYLESHEET => DOCROOT.'themes'.DIRECTORY_SEPARATOR,
	),
	'processor'  => array(
		Assets::JAVASCRIPT => 'jsmin',
		Assets::STYLESHEET => 'cssmin',
	),
);