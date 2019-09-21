<?php
class super_exception_handler {
	protected static $config;
	protected static $exception_handler;
	protected static $data = [];

	public static function initialize($config, $exception_handler)
	{
		self::$config = $config;
		self::$exception_handler = $exception_handler;
		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});
		set_exception_handler(function($e) {
			if (http_response_code() === 200) {
				http_response_code(500);
			}
			$data = [
				'error_message' => self::load_error_message($e->getMessage()),
			];
			if (self::$config['debug']) {
				$data['debug'] = [
					'exception_message' => $e->getMessage(),
					'exception_file' => preg_replace('#^'.getcwd().'#', '.', $e->getFile()),
					'exception_line' => $e->getLine(),
					'debug_message' => self::load_debug_message($e->getMessage()),
				];
			}
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
			return 'An exception occurred';
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
			return 'An exception occurred';
		}
	}

	public static function throw_exception($exception_name, $data = [], $response_code = 500)
	{
		http_response_code($response_code);
		self::$data = $data;
		$caller = debug_backtrace()[0];
		throw new ErrorException($exception_name, 0, 0, $caller['file'], $caller['line']);
	}
}
