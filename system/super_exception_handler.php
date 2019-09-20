<?php
class super_exception_handler {
	protected static $exception_names = [
		'undefined_exception',
		'invalid_request',
		'invalid_number_of_params',
		'invalid_params',
	];

	public static function initialize()
	{
		set_error_handler(function($error_no, $error_msg, $error_file, $error_line, $error_vars) {
			throw new ErrorException($error_msg, 0, $error_no, $error_file, $error_line);
		});
		set_exception_handler(function($e) {
			echo '<b>Code:'.$e->getCode().'</b> '.$e->getMessage().' in <b>'.$e->getFile().'</b> on line <b>'.$e->getLine().'</b>';
		});
	}

	public static function throw_exception($exception_name)
	{
		$caller = debug_backtrace()[0];
		throw new ErrorException('exception message', array_search($exception_name, self::$exception_names), 0, $caller['file'], $caller['line']);
	}
}
