<?
class Cookie extends Kohana_Cookie
{
	public static function set_nosalt($name, $value, $expiration = NULL)
	{
		if ($expiration === NULL)
		{
			// Use the default expiration
			$expiration = Cookie::$expiration;
		}

		if ($expiration !== 0)
		{
			// The expiration is expected to be a UNIX timestamp
			$expiration += time();
		}

		return setcookie($name, $value, $expiration, Cookie::$path, Cookie::$domain, false, false);
	}
}