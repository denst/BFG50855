<?php defined('SYSPATH') or die('No direct script access.');

abstract class Database extends Kohana_Database
{

    // Флаг, который устанавливается в TRUE при старте транзации (для того, чтобы отменить ее в момент ошибки)
	public static $transaction_started = false;

}
