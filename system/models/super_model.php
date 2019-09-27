<?php
class super_model {
	protected static $dbh;

	public function __construct($config, $request)
	{
		$this->config = $config;
		$this->request = $request;
	}

	public function initialize_exception_handler()
	{
		exception_handler::initialize($this->config, function($data){
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
		});
		return $this;
	}

	public function validate_request()
	{
		dispatcher::validate_request($this->config, $this->request);
		return $this;
	}

	public function model($model_name)
	{
		$model_class_name = $model_name.'_model';
		return new $model_class_name($this->config, $this->request);
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

	protected function pbe($statement, $bind_assocs = [])
	{
		$this->sth = $this->get_dbh()->prepare($statement);
		foreach ($bind_assocs as $key => $bind_assoc) {
			$this->sth->bindValue(':'.$key, $bind_assoc['value'], $bind_assoc['data_type']);
		}
		$this->sth->execute();
	}

	protected function get_dbh()
	{
		if (!isset(self::$dbh))
		{
			self::$dbh = new PDO(
				isset($GLOBALS['test_index_config']) ? preg_replace('/dbname=([^;]+)/', 'dbname=${1}_test', $this->config['pdo']['dsn']) : $this->config['pdo']['dsn'],
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

	protected function send_mail($mail_name, $data = [])
	{
		$mail = $this->load_mail($mail_name, $data);
		[$subject, $message] = explode("\n", $mail, 2);
		$params = [
			'to' => $data['to'],
			'subject' => $subject,
			'message' => trim($message)."\n",
			'additional_headers' => isset($data['additional_headers']) ? $data['additional_headers'] : '',
			'additional_parameter' => isset($data['additional_parameter']) ? $data['additional_parameter'] : ''
		];
		if (!isset($GLOBALS['test_index_config'])) {
			if (!call_user_func_array('mb_send_mail', array_values($params))) {
				exception_handler::throw_exception('send_mail_failed', ['config' => $this->config, 'request' => $this->request, 'mail_name' => $mail_name, 'data' => $data, 'params' => $params]);
			}
		}
		return $params;
	}

	protected function load_mail($mail_name, $data = [], $replace_segments = [])
	{
		$segments = array_merge($this->request, $replace_segments);
		$mail_path = fallback::get_fallback_path([
			$this->config['packages'],
			['mails'],
			[$segments['language'], ''],
			[$segments['actor'], ''],
			[$segments['target'], ''],
			[$mail_name.'.php'],
		]);
		if ($mail_path !== null) {
			ob_start();
			require './'.$mail_path;
			return ob_get_clean();
		} else {
			exception_handler::throw_exception('mail_not_found', ['config' => $this->config, 'request' => $this->request, 'mail_name' => $mail_name, 'replace_segments' => $replace_segments], 500, 2);
		}
	}
}
