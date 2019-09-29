<?php
class super_session_handler implements SessionHandlerInterface {
	private $config;
	private $request;
	private $dbh;
	private $save_path;

	public function __construct($config, $request)
	{
		ini_set('session.serialize_handler', 'php_serialize');
		$this->config = $config;
		$this->request = $request;
		$this->dbh = new PDO(
			isset($GLOBALS['test_index_config']) ? preg_replace('/dbname=([^;]+)/', 'dbname=${1}_test', $this->config['pdo']['dsn']) : $this->config['pdo']['dsn'],
			$this->config['pdo']['username'],
			$this->config['pdo']['password'],
			[
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]
		);
	}

	public function close()
	{
		return true;
	}

	public function destroy($id)
	{
		$sth = $this->dbh->prepare('DELETE FROM `session` WHERE `session_id` = :id');
		$sth->bindValue(':id', $id, PDO::PARAM_STR);
		$sth->execute();
		session_unset();
		return true;
	}

	public function gc($maxlifetime)
	{
		$sth = $this->dbh->prepare('SELECT COUNT(*) AS `count` FROM `session` WHERE `session_id` = :id AND `session_microtime` <= :microtime');
		$sth->bindValue(':id', session_id(), PDO::PARAM_STR);
		$sth->bindValue(':microtime', $this->request['microtime'] - $maxlifetime * 1000000, PDO::PARAM_INT);
		$sth->execute();
		if ($sth->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
			session_unset();
		}
		$this->dbh->beginTransaction();
		$sth = $this->dbh->prepare('DELETE `auth_session` FROM `auth_session` NATURAL JOIN `session` WHERE `session_microtime` <= :microtime');
		$sth->bindValue(':microtime', $this->request['microtime'] - $maxlifetime * 1000000, PDO::PARAM_INT);
		$sth->execute();
		$this->dbh->commit();
		$sth = $this->dbh->prepare('DELETE FROM `session` WHERE `session_microtime` <= :microtime');
		$sth->bindValue(':microtime', $this->request['microtime'] - $maxlifetime * 1000000, PDO::PARAM_INT);
		$sth->execute();
		return true;
	}

	public function open($save_path, $session_name)
	{
		return true;
	}

	public function read($id)
	{
		$sth = $this->dbh->prepare('SELECT `session_data` FROM `session` WHERE `session_id` = :id');
		$sth->bindValue(':id', $id, PDO::PARAM_STR);
		$sth->execute();
		$data = json_decode($sth->fetch(PDO::FETCH_ASSOC)['session_data'], true);
		$sth = $this->dbh->prepare('SELECT `auth_session_actor`, `auth_session_id`, `auth_session_data` FROM `auth_session` WHERE `session_id` = :id');
		$sth->bindValue(':id', $id, PDO::PARAM_STR);
		$sth->execute();
		foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$data['auth'][$row['auth_session_actor']] = array_merge(['id' => $row['auth_session_id']], json_decode($row['auth_session_data'], true));
		}
		return serialize($data);
	}

	public function write($id, $data)
	{
		$value = unserialize($data);
		if (isset($value['auth'])) {
			$auth_values = $value['auth'];
			unset($value['auth']);
		}
		$this->dbh->beginTransaction();
		$sth = $this->dbh->prepare('INSERT INTO `session` SET `session_id` = :id, `session_data` = :data_insert, `session_microtime` = :microtime_insert ON DUPLICATE KEY UPDATE `session_data` = :data_update, `session_microtime` = :microtime_update');
		$sth->bindValue(':id', $id, PDO::PARAM_STR);
		$sth->bindValue(':data_insert', json_encode($value, JSON_UNESCAPED_UNICODE), PDO::PARAM_STR);
		$sth->bindValue(':microtime_insert', $this->request['microtime'], PDO::PARAM_INT);
		$sth->bindValue(':data_update', json_encode($value, JSON_UNESCAPED_UNICODE), PDO::PARAM_STR);
		$sth->bindValue(':microtime_update', $this->request['microtime'], PDO::PARAM_INT);
		$sth->execute();
		$sth = $this->dbh->prepare('DELETE FROM `auth_session` WHERE `session_id` = :id');
		$sth->bindValue(':id', $id, PDO::PARAM_STR);
		$sth->execute();
		if (isset($auth_values)) {
			foreach ($auth_values as $auth_actor => $auth_value) {
				$auth_id = $auth_value['id'];
				unset($auth_value['id']);
				$sth = $this->dbh->prepare('INSERT INTO `auth_session` SET `session_id` = :id, `auth_session_actor` = :auth_actor, `auth_session_id` = :auth_id, `auth_session_data` = :auth_data');
				$sth->bindValue(':id', $id, PDO::PARAM_STR);
				$sth->bindValue(':auth_actor', $auth_actor, PDO::PARAM_STR);
				$sth->bindValue(':auth_id', $auth_id, PDO::PARAM_STR);
				$sth->bindValue(':auth_data', json_encode($auth_value, JSON_UNESCAPED_UNICODE), PDO::PARAM_STR);
				$sth->execute();
			}
		}
		$this->dbh->commit();
		return true;
	}
}
