<?php
class View extends Kohana_View
{
	public static function get_global($key = null)
	{
		if ($key == null)
				return View::$_global_data;
		else if (isset(View::$_global_data[$key]))
				return View::$_global_data[$key];
			else return null;
	}
}

?>
