<?php
class super_exception_handler {
	protected static $data = [];

	public static function initialize()
	{
		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
		set_exception_handler(function($e) {
			$data = [
				'error_message' => self::load_error_message($e->getMessage()),
				'exception_message' => $e->getMessage(),
				'exception_file' => $e->getFile(),
				'exception_line' => $e->getLine(),
				'debug_message' => self::load_debug_message($e->getMessage()),
			];

			echo $data['error_message'].'<br>';
			echo '<b>'.$data['exception_message'].'</b> in <b>'.$data['exception_file'].'</b> on line <b>'.$data['exception_line'].'</b><br>';
			echo $data['debug_message'].'<br>';
		});
	}

	protected static function load_error_message($errstr)
	{
		$error_message_path = fallback::get_fallback_path([
			['system'],
			['error_messages'],
			[$errstr.'.php'],
		]);
		if ($error_message_path !== null) {
			$data = self::$data;
			ob_start();
			require_once $error_message_path;
			return ob_get_clean();
		} else {
			return '';
		}
	}

	protected static function load_debug_message($errstr)
	{
		$debug_message_path = fallback::get_fallback_path([
			['system'],
			['debug_messages'],
			[$errstr.'.php'],
		]);
		if ($debug_message_path !== null) {
			$data = self::$data;
			ob_start();
			require_once $debug_message_path;
			return ob_get_clean();
		} else {
			return '';
		}
	}

	public static function throw_exception($exception_name, $data = [])
	{
		self::$data = $data;
		$caller = debug_backtrace()[0];
		throw new ErrorException($exception_name, 0, 0, $caller['file'], $caller['line']);
	}
}
