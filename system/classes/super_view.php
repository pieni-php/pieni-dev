<?php
class super_view {
	protected $loaded_target_controllers = [];

	public function __construct($config, $request, $target)
	{
		$this->config = $config;
		$this->request = $request;
		$this->target = $target;
	}

	public function load_view($view_name, ...$data_list)
	{
		$data = [];
		foreach ($data_list as $array) {
			$data = array_merge($data, $array);
		}
		core::fallback(
			[
				['views'],
				[$this->request['language'], ''],
				[$this->request['actor'], ''],
				isset($this->target['fallback']) ? [$this->request['target'], $this->target['fallback'], ''] : [$this->request['target'], ''],
				[$view_name.'.php'],
			],
			function ($path, $data) {
				require_once './'.$path;
			},
			function ($file) {
				$this->load_error(500, 'Fallback for view file \''.$file.'\' failed');
			},
			$data
		);
	}

	public function load_error($response_code, $debug)
	{
		ob_clean();
		if ($response_code === 403) {
			header('Location: '.$this->href('auth_'.$this->request['actor'].'/login', ['actor' => $this->config['actors'][0]], true));
			exit;
		}
		http_response_code($response_code);
		$data = [		
			'response_code' => $response_code,
		];
		if ($this->config['debug']) {
			$data['debug'] = $debug;
		}
		$this->load_view('errors/template', $data);
		ob_end_flush();
		exit(1);
	}

	private function h($string)
	{
		echo htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
	}

	public function href($path = '', $override = [], $return = false)
	{
		$href = $this->request['base_url'];
		$language = isset($override['language']) ? $override['language'] : $this->request['language'];
		$actor = isset($override['actor']) ? $override['actor'] : $this->request['actor'];
		if ($language !== $this->config['languages'][0]) $href .= '/'.$language;
		if ($actor !== $this->config['actors'][0]) $href .= '/'.$actor;
		if ($path !== '') $href .= '/'.$path;
		if ($return) {
			return $href;
		}
		$this->h($href);
	}

	public function asset($asset, $data = [])
	{
		core::fallback(
			[
				['assets'],
				[$asset],
			],
			function ($path) {
				$this->h($this->request['base_url'].'/'.$path);
			},
			function ($file) {
				$this->load_error(500, 'Fallback for \''.$file.'\' failed');
			}
		);
	}

	protected function get_super_controller_path()
	{
		return core::fallback(
			[
				['classes'],
				['super_controller.js'],
			],
			function ($path) {
				return $path;
			},
			function ($file) {
				return false;
			}
		);
	}

	protected function get_controller_path()
	{
		return core::fallback(
			[
				['classes'],
				['controller.js'],
			],
			function ($path) {
				return $path;
			},
			function ($file) {
				return false;
			}
		);
	}

	protected function get_super_target_controller_path($target)
	{
		return core::fallback(
			[
				['controllers'],
				isset($this->target['fallback']) ? ['super_'.$target.'_controller.js', 'super_'.$this->target['fallback'].'_controller.js'] :  ['super_'.$target.'_controller.js'],
			],
			function ($path) {
				return $path;
			},
			function ($file) {
				return false;
			}
		);
	}

	protected function get_target_controller_path($target)
	{
		return core::fallback(
			[
				['controllers'],
				isset($this->target['fallback']) ? [$target.'_controller.js', $this->target['fallback'].'_controller.js'] :  [$target.'_controller.js'],
			],
			function ($path) {
				return $path;
			},
			function ($file) {
				return false;
			}
		);
	}

	protected function load_target_controller($target, $has_parent = false)
	{
		$super_target_controller_path = $this->get_super_target_controller_path($target);
		$target_controller_path = $this->get_target_controller_path($target);
		if ($super_target_controller_path !== false || $target_controller_path !== false)
		{
			if (!$has_parent)
			{
				$super_controller_path = $this->get_super_controller_path();
				$controller_path = $this->get_controller_path();
				echo '<script src="'.$this->href($super_controller_path, ['actor' => $this->config['actors'][0]], true).'"></script>'."\n";
				if ($controller_path !== false)
				{
					echo '<script src="'.$this->href($controller_path, ['actor' => $this->config['actors'][0]], true).'"></script>'."\n";
				} else {
					echo '<script>const controller = super_controller;</script>'."\n";
				}
			}
			if ($super_target_controller_path !== false && !in_array($super_target_controller_path, $this->loaded_target_controllers)) {
				$this->loaded_target_controllers[] = $super_target_controller_path;
				echo '<script src="'.$this->href($super_target_controller_path, ['actor' => $this->config['actors'][0]], true).'"></script>'."\n";
			}
			if ($target_controller_path !== false && !in_array($super_target_controller_path, $this->loaded_target_controllers)) {
				$this->loaded_target_controllers[] = $target_controller_path;
				echo '<script src="'.$this->href($target_controller_path, ['actor' => $this->config['actors'][0]], true).'"></script>'."\n";
			}
			if (!$has_parent && isset($this->target['children']))
			{
				foreach ($this->target['children'] as $child_name => $child)
				{
					$this->load_target_controller($child_name, true);
				}
			}
			if (!$has_parent) {
				$this->target['controller_class'] = pathinfo($target_controller_path !== false ? $target_controller_path : $super_target_controller_path)['filename'];
				echo '<script>(new '.$this->target['controller_class'].'('.json_encode($this->config, JSON_UNESCAPED_UNICODE).', '.json_encode($this->request, JSON_UNESCAPED_UNICODE).', '.json_encode($this->target, JSON_UNESCAPED_UNICODE).')).onload();</script>';
			} else {
				$this->target['children'][$target]['controller_class'] = pathinfo($target_controller_path !== false ? $target_controller_path : $super_target_controller_path)['filename'];
			}
		}
	}
}
