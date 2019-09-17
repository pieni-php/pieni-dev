<?php
class super_core {
	static $config;

	public function __construct($_server)
	{
		session_start();
		self::$config = $this->get_config();
		$this->request = $this->get_request($_server);
		$this->target = $this->get_target($this->request['target']);
	}

	private function get_config()
	{
		self::$config = [
			'packages' => ['application', 'system'],
		];
		if (file_exists('./config.php')) {
			self::$config = array_replace_recursive(self::$config, require './config.php');
		}
		$config = self::fallback(
			[
				['config.php'],
			],
			function ($path) {
				return require_once './'.$path;
			},
			function ($file) {
				echo 'Fallback for \''.$file.'\' failed';
				exit(1);
			}
		);
		if (file_exists('./config.php')) {
			$config = array_replace_recursive($config, require './config.php');
		}
		return $config;
	}

	public static function get_target($target, $has_parent = false)
	{
		return self::fallback(
			[
				['targets'],
				[$target.'.php'],
			],
			function ($path, $has_parent) {
				$target = require './'.$path;
				if (!$has_parent && isset($target['has_children'])) {
					$target['children'] = [];
					foreach ($target['has_children'] as $children_name) {
						$target['children'][$children_name] = self::get_target($children_name, true);
					}
				}
				return $target;
			},
			function ($file) {
				return [
				];
			},
			$has_parent
		);
	}

	private function get_request($_server)
	{
		$timeofday = gettimeofday();
		$uri_segments = $_server['PATH_INFO'] === '/' ? [] : explode('/', trim($_server['PATH_INFO'], '/'));
		$request = [];
		$request['base_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']);
		$request['microtime'] = $timeofday['sec'] * 1000000 + $timeofday['usec'];
		$request['_post'] = $_POST;
		$request['token'] = bin2hex(random_bytes(16));
		$request['type'] = isset($uri_segments[0]) && $uri_segments[0] === 'api' ? array_shift($uri_segments) : 'page';
		$request['language'] = isset($uri_segments[0]) && in_array($uri_segments[0], self::$config['languages']) && $uri_segments[0] !== self::$config['languages'][0] ? array_shift($uri_segments) : self::$config['languages'][0];
		$request['actor'] = isset($uri_segments[0]) && in_array($uri_segments[0], self::$config['actors']) && $uri_segments[0] !== self::$config['actors'][0] ? array_shift($uri_segments) : self::$config['actors'][0];
		$request['target'] = isset($uri_segments[0]) && isset(self::$config['actions'][$request['type']][$request['actor']]) && in_array($uri_segments[0], array_keys(self::$config['actions'][$request['type']][$request['actor']])) && $uri_segments[0] !== 'welcome' ? array_shift($uri_segments) : 'welcome';
		$request['action'] = isset($uri_segments[0]) && isset(self::$config['actions'][$request['type']][$request['actor']][$request['target']]) && in_array($uri_segments[0], array_keys(self::$config['actions'][$request['type']][$request['actor']][$request['target']])) && $uri_segments[0] !== 'index' ? array_shift($uri_segments) : 'index';
		$request['argv'] = $uri_segments;
		return $request;
	}

	public function exec_request()
	{
		if ($this->request['type'] === 'page') {
			$this->exec_page_request();
		} else {
			$this->exec_api_request();
		}
	}

	private function exec_page_request()
	{
		ob_start();
		self::fallback(
			[
				['classes/super_view.php'],
			],
			function ($path) {
				require_once './'.$path;
			},
			function ($file) {
				echo 'Fallback for \''.$file.'\' failed';
				exit(1);
			}
		);
		self::fallback(
			[
				['classes/view.php'],
			],
			function ($path) {
				require_once './'.$path;
			},
			function ($file) {
				class_alias('super_view', 'view');
			}
		);
		$this->request_executer = new view(self::$config, $this->request, $this->target);
		$this->validate_request();
		$this->set_exception_handler();
		$this->request_executer->load_view('template');
		ob_end_flush();
	}

	private function exec_api_request()
	{
		$this->request_executer = $this->get_model();
		$this->validate_request();
		$this->request_executer = $this->get_target_model($this->request['target'], $this->target);
		$this->set_exception_handler();
		$result = call_user_func_array([$this->request_executer, $this->request['action']], $this->request['argv']);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	private function validate_request()
	{
		if ($this->request['actor'] !== self::$config['actors'][0] && !isset($_SESSION['auth'][$this->request['actor']])) {
			$this->request_executer->load_error(403, 'Authentication required');
		}
		if (!isset(self::$config['actions'][$this->request['type']][$this->request['actor']]) || !in_array($this->request['target'], array_keys(self::$config['actions'][$this->request['type']][$this->request['actor']]))) {
			$this->request_executer->load_error(404, 'Invalid target \''.$this->request['target'].'\'');
		}
		if (!isset(self::$config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']]) || !in_array($this->request['action'], array_keys(self::$config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']]))) {
			$this->request_executer->load_error(404, 'Invalid action \''.$this->request['action'].'\'');
		}
		if (!isset(self::$config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']][$this->request['action']]['argc']) || count($this->request['argv']) !== self::$config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']][$this->request['action']]['argc']) {
			$this->request_executer->load_error(404, 'Invalid argc');
		}
	}

	protected function get_model()
	{
		self::fallback(
			[
				['classes/super_model.php'],
			],
			function ($path) {
				require_once './'.$path;
			},
			function ($file) {
				echo 'Fallback for \''.$file.'\' failed';
				exit(1);
			}
		);
		self::fallback(
			[
				['classes/model.php'],
			],
			function ($path) {
				require_once './'.$path;
			},
			function ($file) {
				class_alias('super_model', 'model');
			}
		);
	}

	protected function get_target_model($target_name, $target)
	{
		$super_target_model_name = self::fallback(
			[
				['models'],
				isset($target['fallback']) ? ['super_'.$target_name.'_model.php', 'super_'.$target['fallback'].'_model.php'] : ['super_'.$target_name.'_model.php'],
			],
			function ($path) {
				require_once './'.$path;
				return pathinfo($path)['filename'];
			},
			function ($file) {
			}
		);
		$target_model_name = self::fallback(
			[
				['application', 'system'],
				['models'],
				isset($target['fallback']) ? [$target_name.'_model.php', $target['fallback'].'_model.php'] : [$target_name.'_model.php'],
			],
			function ($path) {
				require_once './'.$path;
				return pathinfo($path)['filename'];
			},
			function ($file, $super_target_model_name) {
				if ($super_target_model_name !== null && class_exists($super_target_model_name)) {
					return $super_target_model_name;
				}
				$this->request_executer->load_error(404, 'Fallback for model file \''.$file.'\' failed');
				exit(1);
			},
			$super_target_model_name
		);
		if (!class_exists($target_model_name)) {
			$this->request_executer->load_error(404, 'Class \''.$target_model_name.'\' not found');
		}
		if (!method_exists($target_model_name, $this->request['action'])) {
			$this->request_executer->load_error(404, 'Method \''.$this->request['action'].'\' not found');
		}
		$target_model = new $target_model_name(self::$config, $this->request, $target);
		if (isset($target['children'])) {
			$target_model->children = [];
			foreach ($target['children'] as $child_target_name => $child_target) {
				$target_model->children[$child_target_name] = $this->get_target_model($child_target_name, $child_target);
			}
		}
		return $target_model;
	}

	private function set_exception_handler()
	{
		set_error_handler(function($error_no, $error_msg, $error_file, $error_line, $error_vars) {
			throw new ErrorException($error_msg, 0, $error_no, $error_file, $error_line);
		});
		set_exception_handler(function($throwable) {
			$this->request_executer->load_error(500, $throwable->getMessage().' in '.$throwable->getFile().' on line '.$throwable->getLine());
		});
	}

	public static function fallback($seeds, $on_succeeded, $on_failed, $depends = null)
	{
		array_unshift($seeds, self::$config['packages']);
		foreach (self::cartesian($seeds) as $array) {
			$path = './'.implode('/', $array);
			if (file_exists($path)) {
				return $on_succeeded(preg_replace('/^\.\//', '', preg_replace('/\/+/', '/', $path)), $depends);
			}
		}
		return $on_failed(end($seeds)[0], $depends);
	}

	private static function cartesian($seeds)
	{
		return count($seeds) === 1 ? array_chunk($seeds[0], 1) : (function ($seeds) {
			$ret = [];
			foreach ($seeds[0] as $v0) {
				foreach (self::cartesian(array_slice($seeds, 1)) as $v1) {
					$ret[] = array_merge([$v0], $v1);
				}
			}
			return $ret;
		})($seeds);
	}
}
