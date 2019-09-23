<?php
class super_view {
	protected $loaded_controller_class_names = [];

	public function __construct($config, $request)
	{
		ob_start();
		$this->config = $config;
		$this->request = $request;
	}

	public function initialize_exception_handler()
	{
		exception_handler::initialize($this->config, function($data){
			ob_end_clean();
			$this->load_view('template', $data, ['target' => 'exception', 'action' => 'index', 'params' => []]);
		});
		return $this;
	}

	public function validate_request()
	{
		dispatcher::validate_request($this->config, $this->request);
		return $this;
	}

	public function load_view($view_name, $data = [], $replace_segments = [])
	{
		$segments = array_merge($this->request, $replace_segments);
		$view_path = fallback::get_fallback_path([
			$this->config['packages'],
			['views'],
			[$segments['language'], ''],
			[$segments['actor'], ''],
			[$segments['target'], ''],
			[$view_name.'.php'],
		]);
		if ($view_path !== null) {
			return require './'.$view_path;
		} else {
			exception_handler::throw_exception('view_not_found', ['config' => $this->config, 'request' => $this->request, 'view_name' => $view_name, 'replace_segments' => $replace_segments]);
		}
	}

	protected function exec_action_method($config, $request)
	{
		$this->load_super_controller_class($config);
		$this->load_controller_class($config);
		$this->load_super_target_controller_class($config, $request['target']);
		$this->load_target_controller_class($config, $request['target']);
		if ($this->loaded_controller_class_names !== []) {
			$params_str = $request['params'] === [] ? '' : '\''.implode('\', \'', $request['params']).'\'';
			echo '<script>$(() => new controller('.
				json_encode($config, JSON_UNESCAPED_UNICODE).', '.
				json_encode($request, JSON_UNESCAPED_UNICODE).
			').controller(\''.$request['target'].'\').'.$request['action'].'('.$params_str.'));</script>'."\n";
		}
	}

	protected function load_super_controller_class($config)
	{
		$super_controller_path = fallback::get_fallback_path([
			$config['packages'],
			['controllers'],
			['super_controller.js'],
		]);
		echo '<script src="'.$this->href($super_controller_path, [], true).'"></script>'."\n";
	}

	protected function load_controller_class($config)
	{
		$controller_path = fallback::get_fallback_path([
			$config['packages'],
			['controllers'],
			['controller.js'],
		]);
		if ($controller_path !== null) {
			echo '<script src="'.$this->href($controller_path, [], true).'"></script>'."\n";
		} else {
			echo '<script>const controller = super_controller;</script>'."\n";
		}
	}

	protected function load_super_target_controller_class($config, $controller_name)
	{
		$super_controller_path = fallback::get_fallback_path([
			$config['packages'],
			['controllers'],
			['super_'.$controller_name.'_controller.js'],
		]);
		if ($super_controller_path !== null) {
			$this->loaded_controller_class_names[] = pathinfo($super_controller_path, PATHINFO_FILENAME);
			echo '<script src="'.$this->href($super_controller_path, true).'"></script>'."\n";
		}
	}

	protected function load_target_controller_class($config, $controller_name)
	{
		$controller_path = fallback::get_fallback_path([
			$config['packages'],
			['controllers'],
			[$controller_name.'_controller.js'],
		]);
		if ($controller_path !== null) {
			$this->loaded_controller_class_names[] = pathinfo($controller_path, PATHINFO_FILENAME);
			echo '<script src="'.$this->href($controller_path, [], true).'"></script>'."\n";
		} elseif (in_array('super_'.$controller_name.'_controller', $this->loaded_controller_class_names)) {
			echo '<script>const '.$controller_name.'_controller = super_'.$controller_name.'_controller;</script>'."\n";
		}
	}

	protected function load_referable($referable_file, $replace_segments = [])
	{
		$segments = array_merge($this->request, $replace_segments);
		$referable_path = fallback::get_fallback_path([
			$this->config['packages'],
			['referables'],
			[$segments['language'], ''],
			[$segments['actor'], ''],
			[$segments['target'], ''],
			[$referable_file],
		]);
		if ($referable_path !== null) {
			$this->href($referable_path);
		} else {
			exception_handler::throw_exception('referable_not_found', ['config' => $this->config, 'request' => $this->request, 'referable_file' => $referable_file, 'replace_segments' => $replace_segments]);		}
	}

	protected function href($path, $replace_segments = [], $return = false)
	{
		$href = $this->request['base_url'];
		$segments = array_merge($this->request, $replace_segments);
		if ($segments['language'] !== $this->config['request']['languages'][0]) $href .= '/'.$segments['language'];
		if ($segments['actor'] !== $this->config['request']['actors'][0]) $href .= '/'.$segments['actor'];
		$href .= '/'.$path;
		if ($return) {
			return $href;
		}
		$this->h($href);
	}

	protected function h($string)
	{
		echo htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
	}
}
