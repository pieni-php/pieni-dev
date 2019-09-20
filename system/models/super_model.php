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
		exception_handler::initialize();
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
}
