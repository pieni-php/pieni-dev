<?php
class super_dispatcher {
	public function dispatch($index_config)
	{
		$config = $this->load_config($index_config);
		if (file_exists('./development_config.php')) {
			$config = array_replace_recursive($config, require './development_config.php');
		}
		$request = $this->get_request($config);
		$this->load_super_exception_handler_class($config);
		$this->load_exception_handler_class($config);
		if ($request['type'] === 'page') {
			$this->exec_page_request($config, $request);
		} else {
			$this->exec_api_request($config, $request);
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
		$segments = $_SERVER['PATH_INFO'] === '/' ? [] : explode('/', trim($_SERVER['PATH_INFO'], '/'));
		$request = [];
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

	protected function exec_page_request($config, $request)
	{
		$this->load_super_view_class($config);
		$this->load_view_class($config);
		(new view($config, $request))->initialize_exception_handler()->validate_request()->load_view('template');
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

	protected function exec_api_request($config, $request)
	{
		$this->load_super_model_class($config);
		$this->load_model_class($config);
		$this->load_super_target_model_class($config, $request['target']);
		$this->load_target_model_class($config, $request['target']);
		$result = call_user_func_array([(new model($config, $request))->initialize_exception_handler()->validate_request()->model($request['target']), $request['action']], $request['params']);
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

	protected function load_super_target_model_class($config, $model_name)
	{
		$super_model_path = fallback::get_fallback_path([
			$config['packages'],
			['models'],
			['super_'.$model_name.'_model.php'],
		]);
		if ($super_model_path !== null) {
			require_once './'.$super_model_path;
		}
	}

	protected function load_target_model_class($config, $model_name)
	{
		$model_path = fallback::get_fallback_path([
			$config['packages'],
			['models'],
			[$model_name.'_model.php'],
		]);
		if ($model_path !== null) {
			require_once './'.$model_path;
		} elseif (class_exists('super_'.$model_name.'_model')) {
			class_alias('super_'.$model_name.'_model', $model_name.'_model');
		} else {
			(new model($config, []))->initialize_exception_handler();
			exception_handler::throw_exception('model_not_found', ['config' => $config, 'model_name' => $model_name]);
		}
	}

	public static function validate_request($config, $request)
	{
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
