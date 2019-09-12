<?php
class super_view {
	public function __construct($config, $request, $target)
	{
		$this->config = $config;
		$this->request = $request;
		$this->target = $target;
	}

	public function load_view($view_name, $data = [])
	{
		core::fallback(
			[
				['application', 'system'],
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
				['application', 'system'],
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
}
