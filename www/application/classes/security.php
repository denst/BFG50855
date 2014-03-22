<?

class Security extends Kohana_Security {

	static function check_token()
	{
		return self::check(Arr::get($_REQUEST,TOKEN));
	}

}

?>
