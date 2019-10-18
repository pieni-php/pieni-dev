<?php
class super_model {
	protected $config;
	protected $request;
	protected $target;
	protected static $dbh;

	public function __construct($config, $request, $target)
	{
		$this->config = $config;
		$this->request = $request;
		$this->target = $target;
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
		return new $model_class_name($this->config, $this->request, $this->target);
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
		ob_start();
		$this->load_mail($mail_name, $data);
		$mail = ob_get_clean();
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
			isset($this->target['fallback']) ? [$segments['target'], $this->target['fallback'], ''] : [$segments['target'], ''],
			[$mail_name.'.php'],
		]);
		if ($mail_path !== null) {
			require './'.$mail_path;
		} else {
			exception_handler::throw_exception('mail_not_found', ['config' => $this->config, 'request' => $this->request, 'target' => $this->target, 'mail_name' => $mail_name, 'replace_segments' => $replace_segments], 500, 2);
		}
	}

	protected function href($path, $replace_segments = [])
	{
		$href = $this->request['base_url'];
		$segments = array_merge($this->request, $replace_segments);
		if ($segments['language'] !== $this->config['request']['languages'][0]) $href .= '/'.$segments['language'];
		if ($segments['actor'] !== $this->config['request']['actors'][0]) $href .= '/'.$segments['actor'];
		$href .= '/'.$path;
		echo $href;
	}

	protected function get_select_clause()
	{
		$column_arr = [];
		foreach (array_merge([$this->target['target'].'_id', $this->target['target'].'_name'], ($this->request['action'] !== 'child_of' ? $this->target['action_column_names'][$this->request['action']] : $this->target['as_child_of'][$this->request['params'][0]]['action_column_names'][$this->request['action']])) as $column_name) {
			$column = $this->target['columns'][$column_name];
			$column_arr[] = (isset($column['expr']) ? $column['expr'] : '`'.$column_name.'`').' AS `'.$column_name.'`';
		}
		return implode(', ', $column_arr);
	}

	protected function get_join_tables()
	{
		if (!isset($this->target['join_tables']) || count($this->target['join_tables']) === 0) return '';
		$join_tables = [];
		foreach ($this->target['join_tables'] as $join_table_name => $join_table) {
			$join_tables[] = 'NATURAL JOIN '.(isset($column['expr']) ? $column['expr'] : '`'.$join_table_name.'`').' AS `'.$join_table_name.'`';
		}
		return implode("\n", $join_tables);
	}

	protected function get_set_clause()
	{
		$array = [];
		foreach ($this->target['action_column_names'][$this->request['action']] as $column_name) {
			$array[] = '`'.$column_name.'` = :'.$column_name;
		}
		return implode(' AND ', $array);
	}

	protected function get_bind_assocs($data)
	{
		$bind_assocs = [];
		foreach ($this->target['action_column_names'][$this->request['action']] as $column_name) {
			$column = $this->target['columns'][$column_name];
			$bind_assocs[$column_name] = [
				'value' => $data[$column_name],
				'data_type' => $column['data_type'],
			];
		}
		return $bind_assocs;
	}

	protected function load_target($target_name)
	{
		$target_path = fallback::get_fallback_path([
			$this->config['packages'],
			['targets'],
			[$target_name.'.php'],
		]);
		if ($target_path !== null) {
			return require_once './'.$target_path;
		} else {
			exception_handler::throw_exception('target_not_found');
		}
	}
}
