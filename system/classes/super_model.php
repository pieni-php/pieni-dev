<?php
class super_model {
	private static $dbh;

	public function __construct($config, $request, $target)
	{
		$this->config = $config;
		$this->request = $request;
		$this->target = $target;
	}

	protected function get_dbh()
	{
		if (!isset(self::$dbh))
		{
			self::$dbh = new PDO(
				$this->config['pdo']['dsn'],
				$this->config['pdo']['username'],
				$this->config['pdo']['password'],
				[
					PDO::ATTR_EMULATE_PREPARES => false,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				]
			);
		}
		return self::$dbh;
	}

	public function load_error($response_code, $debug)
	{
		http_response_code($response_code);
		$data = [
			'response_code' => $response_code,
		];
		if ($this->config['debug']) {
			$data['debug'] = $debug;
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit(1);
	}

	protected function pbe($statement, $bind_assocs = [])
	{
		$this->sth = $this->get_dbh()->prepare($statement);
		foreach ($bind_assocs as $key => $bind_assoc) {
			$this->sth->bindValue(':'.$key, $bind_assoc['value'], $bind_assoc['data_type']);
		}
		$this->sth->execute();
	}

	protected function rows($statement, $bind_assoc = [])
	{
		$this->pbe($statement, $bind_assoc);
		return $this->sth->fetchAll(PDO::FETCH_ASSOC);
	}

	protected function row($statement, $bind_assoc = [])
	{
		$this->pbe($statement, $bind_assoc);
		return $this->sth->fetch(PDO::FETCH_ASSOC);
	}

	protected function send_mail($mail_name, $data = [])
	{
		ob_start();
		$this->load_mail($mail_name, $data);
		$mail = ob_get_clean();
		list($subject, $message) = explode("\n", $mail, 2);
		mb_send_mail(
			$data['to'],
			$subject,
			trim($message)."\n",
			isset($data['additional_headers']) ? $data['additional_headers'] : null,
			isset($data['additional_parameter']) ? $data['additional_parameter'] : null
		);
	}

	protected function load_mail($mail_name, $data = [])
	{
		return core::fallback(
			[
				['application', 'system'],
				['mails'],
				[$this->request['language'], ''],
				[$this->request['actor'], ''],
				isset($this->target['fallback']) ? [$this->request['target'], $this->target['fallback'], ''] : [$this->request['target'], ''],
				[$mail_name.'.php'],
			],
			function ($path, $data) {
				require_once './'.$path;
			},
			function ($file) {
				$this->load_error(500, 'Fallback for mail file \''.$file.'\' failed');
			},
			$data
		);
	}

	protected function href($path = '', $override = [])
	{
		$href = $this->request['base_url'];
		$language = isset($override['language']) ? $override['language'] : $this->request['language'];
		$actor = isset($override['actor']) ? $override['actor'] : $this->request['actor'];
		if ($language !== $this->config['languages'][0]) $href .= '/'.$language;
		if ($actor !== $this->config['actors'][0]) $href .= '/'.$actor;
		if ($path !== '') $href .= '/'.$path;
		echo $href;
	}

	protected function get_set_clause($columns)
	{
		$array = [];
		foreach ($columns as $key => $column) {
			$array[] = '`'.$key.'` = :'.$key;
		}
		return implode(' AND ', $array);
	}

	protected function get_bind_assocs($columns, $data)
	{
		$bind_assocs = [];
		foreach ($columns as $key => $column) {
			$bind_assocs[$key] = [
				'value' => $data[$key],
				'data_type' => $column['data_type'],
			];
		}
		return $bind_assocs;
	}
}
