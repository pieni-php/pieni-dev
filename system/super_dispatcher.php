<?php
class super_dispatcher {
	public function dispatch($index_config)
	{
		$config = $this->load_config($index_config);
		if (isset($GLOBALS['test_index_config'])) {
			$config = array_replace_recursive($config, $GLOBALS['test_index_config']);
		} elseif (file_exists('./development_config.php')) {
			$config = array_replace_recursive($config, require './development_config.php');
		}
		$this->load_super_exception_handler_class($config);
		$this->load_exception_handler_class($config);
		$request = $this->get_request($config);
		$target = $this->load_target($config, $request['target']);
		if (isset($config['session']['use']) && $config['session']['use']) {
			if (isset($config['session']['database']) && $config['session']['database']) {
				$this->load_super_session_handler_class($config);
				$this->load_session_handler_class($config);
				session_set_save_handler(new super_session_handler($config, $request));
			}
			if (isset($config['session']['lifetime'])) {
				ini_set('session.gc_maxlifetime', $config['session']['lifetime']);
			}
			session_set_cookie_params($config['session']['lifetime'], dirname($_SERVER['SCRIPT_NAME']), $_SERVER['SERVER_NAME']);
			if (isset($_COOKIE['PHPSESSID'])) {
				setcookie('PHPSESSID', $_COOKIE['PHPSESSID'], intval($request['microtime'] / 1000000 + $config['session']['lifetime']), dirname($_SERVER['SCRIPT_NAME']), $_SERVER['SERVER_NAME']);
			}
			session_start();
			session_gc();
		}
		if ($request['type'] === 'page') {
			$this->exec_page_request($config, $request, $target);
		} else {
			$this->exec_api_request($config, $request, $target);
		}
	}

	protected function load_config($index_config)
	{
		$config_path = fallback::get_fallback_path([
			$index_config['packages'],
			['config.php'],
		]);
		return require_once './'.$config_path;
	}

	protected function load_super_session_handler_class($config)
	{
		$super_session_handler_path = fallback::get_fallback_path([
			$config['packages'],
			['super_session_handler.php'],
		]);
		require_once './'.$super_session_handler_path;
	}

	protected function load_session_handler_class($config)
	{
		$session_handler_path = fallback::get_fallback_path([
			$config['packages'],
			['session_handler.php'],
		]);
		if ($session_handler_path !== null) {
			require_once './'.$session_handler_path;
		} else {
			class_alias('super_session_handler', 'session_handler');
		}
	}

	protected function load_super_exception_handler_class($config)
	{
		$super_exception_handler_path = fallback::get_fallback_path([
			$config['packages'],
			['super_exception_handler.php'],
		]);
		require_once './'.$super_exception_handler_path;
	}

	protected function load_exception_handler_class($config)
	{
		$exception_handler_path = fallback::get_fallback_path([
			$config['packages'],
			['exception_handler.php'],
		]);
		if ($exception_handler_path !== null) {
			require_once './'.$exception_handler_path;
		} else {
			class_alias('super_exception_handler', 'exception_handler');
		}
	}

	protected function get_request($config)
	{
		$request = [];
		$timeofday = gettimeofday();
		$request['microtime'] = isset($GLOBALS['test_params']['microtime']) ? $GLOBALS['test_params']['microtime'] : $timeofday['sec'] * 1000000 + $timeofday['usec'];
		$request['token'] = isset($GLOBALS['test_params']['token']) ? $GLOBALS['test_params']['token'] : bin2hex(random_bytes(16));
		$segments = $_SERVER['PATH_INFO'] === '/' ? [] : explode('/', trim($_SERVER['PATH_INFO'], '/'));
		$request['base_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']);
		$request['type'] = isset($segments[0]) && $segments[0] === 'api' ? array_shift($segments) : 'page';
		$request['language'] = isset($segments[0]) && in_array($segments[0], array_slice($config['request']['languages'], 1)) ? array_shift($segments) : $config['request']['languages'][0];
		$request['actor'] = isset($segments[0]) && in_array($segments[0], array_slice($config['request']['actors'], 1)) ? array_shift($segments) : $config['request']['actors'][0];
		$request['target'] =
			isset($segments[0]) &&
			isset($config['request']['param_patterns'][$request['type']][$request['actor']]) &&
			in_array($segments[0], array_keys($config['request']['param_patterns'][$request['type']][$request['actor']])) &&
			$segments[0] !== 'welcome' ?
				array_shift($segments) :
				'welcome'
		;
		$request['action'] =
			isset($segments[0]) &&
			isset($config['request']['param_patterns'][$request['type']][$request['actor']][$request['target']]) &&
			in_array($segments[0], array_keys($config['request']['param_patterns'][$request['type']][$request['actor']][$request['target']])) &&
			$segments[0] !== 'index' ?
				array_shift($segments) :
				'index'
		;
		$request['params'] = $segments;
		return $request;
	}

	protected function load_target($config, $target_name)
	{
		$target_path = fallback::get_fallback_path([
			$config['packages'],
			['targets'],
			[$target_name.'.php'],
		]);
		if ($target_path !== null) {
			return require_once './'.$target_path;
		} else {
			return [];
		}
	}

	protected function exec_page_request($config, $request, $target)
	{
		$this->load_super_view_class($config);
		$this->load_view_class($config);
		(new view($config, $request, $target))->initialize_exception_handler()->validate_request()->load_view('template');
	}

	protected function load_super_view_class($config)
	{
		$super_view_path = fallback::get_fallback_path([
			$config['packages'],
			['views'],
			['super_view.php'],
		]);
		require_once './'.$super_view_path;
	}

	protected function load_view_class($config)
	{
		$view_path = fallback::get_fallback_path([
			$config['packages'],
			['views'],
			['view.php'],
		]);
		if ($view_path !== null) {
			require_once './'.$view_path;
		} else {
			class_alias('super_view', 'view');
		}
	}

	protected function exec_api_request($config, $request, $target)
	{
		$this->load_super_model_class($config);
		$this->load_model_class($config);
		$this->load_super_target_model_class($config, $target, $request['target']);
		$this->load_target_model_class($config, $target, $request['target']);
		$result = call_user_func_array([(new model($config, $request, $target))->initialize_exception_handler()->validate_request()->model($request['target']), $request['action']], $request['params']);
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	protected function load_super_model_class($config)
	{
		$super_model_path = fallback::get_fallback_path([
			$config['packages'],
			['models'],
			['super_model.php'],
		]);
		require_once './'.$super_model_path;
	}

	protected function load_model_class($config)
	{
		$model_path = fallback::get_fallback_path([
			$config['packages'],
			['models'],
			['model.php'],
		]);
		if ($model_path !== null) {
			require_once './'.$model_path;
		} else {
			class_alias('super_model', 'model');
		}
	}

	protected function load_super_target_model_class($config, $target, $model_name)
	{
		$super_model_path = fallback::get_fallback_path([
			$config['packages'],
			['models'],
			['super_'.$model_name.'_model.php'],
		]);
		if ($super_model_path !== null) {
			require_once './'.$super_model_path;
		} elseif (isset($target['fallback'])) {
			$super_model_path = fallback::get_fallback_path([
				$config['packages'],
				['models'],
				['super_'.$target['fallback'].'_model.php'],
			]);
			if ($super_model_path !== null) {
				require_once './'.$super_model_path;
				class_alias('super_'.$target['fallback'].'_model', 'super_'.$model_name.'_model');
			}
		}
	}

	protected function load_target_model_class($config, $target, $model_name)
	{
		$model_path = fallback::get_fallback_path([
			$config['packages'],
			['models'],
			[$model_name.'_model.php'],
		]);
		if ($model_path !== null) {
			require_once './'.$model_path;
		} elseif (isset($target['fallback'])) {
			$model_path = fallback::get_fallback_path([
				$config['packages'],
				['models'],
				[$target['fallback'].'_model.php'],
			]);
			if ($model_path !== null) {
				require_once './'.$model_path;
				class_alias($target['fallback'].'_model', $model_name.'_model');
			} elseif (class_exists('super_'.$model_name.'_model')) {
				class_alias('super_'.$model_name.'_model', $model_name.'_model');
			} else {
				(new model($config, [], []))->initialize_exception_handler();
				exception_handler::throw_exception('model_not_found', ['config' => $config, 'target' => $target, 'model_name' => $model_name]);
			}
		} elseif (class_exists('super_'.$model_name.'_model')) {
			class_alias('super_'.$model_name.'_model', $model_name.'_model');
		} else {
			(new model($config, [], []))->initialize_exception_handler();
			exception_handler::throw_exception('model_not_found', ['config' => $config, 'target' => $target, 'model_name' => $model_name]);
		}
	}

	public static function validate_request($config, $request)
	{
		if ($request['actor'] !== $config['request']['actors'][0] && !isset($_SESSION['auth'][$request['actor']])) {
			exception_handler::throw_exception('authentication_required', ['config' => $config, 'request' => $request], 403, 0);
		}
		if (!isset($config['request']['param_patterns'][$request['type']][$request['actor']][$request['target']][$request['action']])) {
			exception_handler::throw_exception('invalid_request', ['config' => $config, 'request' => $request], 404, 0);
		}
		if (count($request['params']) !== count($config['request']['param_patterns'][$request['type']][$request['actor']][$request['target']][$request['action']])) {
			exception_handler::throw_exception('invalid_number_of_request_params', ['config' => $config, 'request' => $request], 404, 0);
		}
		foreach ($config['request']['param_patterns'][$request['type']][$request['actor']][$request['target']][$request['action']] as $i => $param_pattern) {
			if (!preg_match($param_pattern, $request['params'][$i])) {
				exception_handler::throw_exception('invalid_request_params', ['config' => $config, 'request' => $request], 404, 0);
			}
		}
	}
}
