<?php
class super_core {
	public function __construct($_server)
	{
		session_start();
		$this->config = $this->get_config();
		$this->request = $this->get_request($_server);
		$this->target = $this->get_target();
	}

	private function get_config()
	{
		return self::fallback(
			[
				['application', 'system'],
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
	}

	private function get_target()
	{
		return self::fallback(
			[
				['application', 'system'],
				['targets'],
				[$this->request['target'].'.php'],
			],
			function ($path) {
				return require_once './'.$path;
			},
			function ($file) {
				return [
				];
			}
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
		$request['language'] = isset($uri_segments[0]) && in_array($uri_segments[0], $this->config['languages']) && $uri_segments[0] !== $this->config['languages'][0] ? array_shift($uri_segments) : $this->config['languages'][0];
		$request['actor'] = isset($uri_segments[0]) && in_array($uri_segments[0], $this->config['actors']) && $uri_segments[0] !== $this->config['actors'][0] ? array_shift($uri_segments) : $this->config['actors'][0];
		$request['target'] = isset($uri_segments[0]) && isset($this->config['actions'][$request['type']][$request['actor']]) && in_array($uri_segments[0], array_keys($this->config['actions'][$request['type']][$request['actor']])) && $uri_segments[0] !== 'welcome' ? array_shift($uri_segments) : 'welcome';
		$request['action'] = isset($uri_segments[0]) && isset($this->config['actions'][$request['type']][$request['actor']][$request['target']]) && in_array($uri_segments[0], array_keys($this->config['actions'][$request['type']][$request['actor']][$request['target']])) && $uri_segments[0] !== 'index' ? array_shift($uri_segments) : 'index';
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
				['application', 'system'],
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
				['application', 'system'],
				['classes/view.php'],
			],
			function ($path) {
				require_once './'.$path;
			},
			function ($file) {
				class_alias('super_view', 'view');
			}
		);
		$this->request_executer = new view($this->config, $this->request, $this->target);
		$this->validate_request();
		$this->set_exception_handler();
		$this->request_executer->load_view('template');
		ob_end_flush();
	}

	private function exec_api_request()
	{
		$this->request_executer = $this->get_model();
		$this->set_exception_handler();
		$result = call_user_func_array([$this->request_executer, $this->request['action']], $this->request['argv']);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	private function validate_request()
	{
		if ($this->request['actor'] !== $this->config['actors'][0] && !isset($_SESSION['auth'][$this->request['actor']])) {
			$this->request_executer->load_error(403, 'Authentication required');
		}
		if (!isset($this->config['actions'][$this->request['type']][$this->request['actor']]) || !in_array($this->request['target'], array_keys($this->config['actions'][$this->request['type']][$this->request['actor']]))) {
			$this->request_executer->load_error(404, 'Invalid target \''.$this->request['target'].'\'');
		}
		if (!isset($this->config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']]) || !in_array($this->request['action'], array_keys($this->config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']]))) {
			$this->request_executer->load_error(404, 'Invalid action \''.$this->request['action'].'\'');
		}
		if (!isset($this->config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']][$this->request['action']]['argc']) || count($this->request['argv']) !== $this->config['actions'][$this->request['type']][$this->request['actor']][$this->request['target']][$this->request['action']]['argc']) {
			$this->request_executer->load_error(404, 'Invalid argc');
		}
	}

	protected function get_model()
	{
		self::fallback(
			[
				['application', 'system'],
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
				['application', 'system'],
				['classes/model.php'],
			],
			function ($path) {
				require_once './'.$path;
			},
			function ($file) {
				class_alias('super_model', 'model');
			}
		);
		$this->request_executer = new model($this->config, $this->request, $this->target);
		$this->validate_request();
		$super_model_name = self::fallback(
			[
				['application', 'system'],
				['models'],
				isset($this->target['fallback']) ? ['super_'.$this->request['target'].'_model.php', 'super_'.$this->target['fallback'].'_model.php'] : ['super_'.$this->request['target'].'_model.php'],
			],
			function ($path) {
				require_once './'.$path;
				return pathinfo($path)['filename'];
			},
			function ($file) {
			}
		);
		$model_name = self::fallback(
			[
				['application', 'system'],
				['models'],
				isset($this->target['fallback']) ? [$this->request['target'].'_model.php', $this->target['fallback'].'_model.php'] : [$this->request['target'].'_model.php'],
			],
			function ($path) {
				require_once './'.$path;
				return pathinfo($path)['filename'];
			},
			function ($file, $super_model_name) {
				if ($super_model_name !== null && class_exists($super_model_name)) {
					return $super_model_name;
				}
				$this->request_executer->load_error(404, 'Fallback for model file \''.$file.'\' failed');
				exit(1);
			},
			$super_model_name
		);
		if (!class_exists($model_name)) {
			$this->request_executer->load_error(404, 'Class \''.$model_name.'\' not found');
		}
		if (!method_exists($model_name, $this->request['action'])) {
			$this->request_executer->load_error(404, 'Method \''.$this->request['action'].'\' not found');
		}
		return new $model_name($this->config, $this->request, $this->target);
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
