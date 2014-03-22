<?php

class Handler
{
    public static function exception(Exception $e)
    {
        switch (get_class($e))
        {
            case 'HTTP_Exception_404':
                header('Content-Type: text/html; charset=utf-8');
                //header("Status: 404 Not Found");
                header("Status: 301 Moved Permamenty");
				echo Request::factory('/message/error/'.$e->getCode())->execute();
				return TRUE;
			break;
            case 'Database_Exception':
				self::database_error($e);
                return TRUE;
			break;
            case 'ORM_Validation_Exception':
				self::database_error($e);
                return TRUE;
			break;
            default:
                return Kohana_Exception::handler($e);
			break;
        }
    }

    public static function error(ErrorException $e)
    {
		//echo debug::vars($e);
		echo('проиозшла некая ошибка');
    }

	public static function database_error($e)
	{
		if (Database::$transaction_started) {
			// Если была начата транзакция, откатим изменения
			Database::instance()->rollback();
		}

        Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));
        Kohana::$log->add(Log::STRACE, $e->getTraceAsString());

        $req = Request::initial();

		if (!empty($req) && $req->is_ajax()) {

			$response = new Response;
			$response->status(200);
			//$message = '{"error":"yes", message: "' . $e->getMessage() . '"}';

            if (Kohana::$environment == Kohana::PRODUCTION)
            {
                $message = '[{"type":"error","data":"Введены неверные параметры"}]';
            } else {
                $message = '[{"type":"error","data":"' . $e->getMessage() . '"}]';
            }

			echo $response->body($message)->send_headers()->body();

		} else {

			$response = new Response;
			$response->status(200);
			$message = 'Извините, но при обработке введенных данных произошла ошибка. Попробуйте повторить операцию через какое-то время.';
			echo $response->body($message)->send_headers()->body();

		}
	}
}