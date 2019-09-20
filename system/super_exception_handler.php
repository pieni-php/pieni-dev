<?php
class super_exception_handler {
	protected static $data = [];
	protected static $exception_handler = [];

	public static function initialize($exception_handler)
	{
		self::$exception_handler = $exception_handler;
		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
		set_exception_handler(function($e) {
			$data = [
				'error_message' => self::load_error_message($e->getMessage()),
				'debug' => [
					'exception_message' => $e->getMessage(),
					'exception_file' => $e->getFile(),
					'exception_line' => $e->getLine(),
					'debug_message' => self::load_debug_message($e->getMessage()),
				],
			];
			(self::$exception_handler)($data);
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
