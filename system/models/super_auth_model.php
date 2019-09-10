<?php
class super_auth_model extends model {
	public function join()
	{
		$actor = $this->row('SELECT `'.$this->target['table'].'_id` FROM `auth_'.$this->target['actor'].'` WHERE `'.$this->target['table'].'_email` = :email', [
			'email' => [
				'value' => $this->request['_post']['email'],
				'data_type' => PDO::PARAM_STR,
			],
		]);
		if ($actor !== false) {
			$this->load_error(500, 'Registered email');
		}
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_register_attempted` SET `'.$this->target['table'].'_email` = :email, `auth_'.$this->target['actor'].'_register_attempted_token` = :token, `auth_'.$this->target['actor'].'_register_attempted_microtime` = :microtime', [
			'email' => [
				'value' => $this->request['_post']['email'],
				'data_type' => PDO::PARAM_STR,
			],
			'token' => [
				'value' => $this->request['token'],
				'data_type' => PDO::PARAM_STR,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->send_mail('join', [
			'to' => $this->request['_post']['email'],
			'id' => $this->get_dbh()->lastInsertId(),
			'token' => $this->request['token'],
		]);
	}

	public function register($id, $token)
	{
		$attempt = $this->row('SELECT `auth_'.$this->target['actor'].'_register_attempted_id`, `'.$this->target['table'].'_email`, `auth_'.$this->target['actor'].'_register_attempted_microtime` AS `microtime` FROM `auth_'.$this->target['actor'].'_register_attempted` WHERE `auth_'.$this->target['actor'].'_register_attempted_id` = :id AND `auth_'.$this->target['actor'].'_register_attempted_token` = :token', [
			'id' => [
				'value' => $id,
				'data_type' => PDO::PARAM_INT,
			],
			'token' => [
				'value' => $token,
				'data_type' => PDO::PARAM_STR,
			],
		]);
		if ($attempt === false) {
			$this->load_error(500, 'Invalid token');
		}
		if ($attempt['microtime'] < $this->request['microtime'] - $this->target['token_expire_minutes'] * 60 * 1000000) {
			$this->load_error(500, 'Expired token');
		}
		$last = $this->row('
			SELECT `auth_'.$this->target['actor'].'_register_attempted_id`
			FROM `auth_'.$this->target['actor'].'_register_succeeded`
			NATURAL JOIN `auth_'.$this->target['actor'].'_register_attempted`
			WHERE `'.$this->target['table'].'_email` = :email
			ORDER BY `auth_'.$this->target['actor'].'_register_succeeded_id` DESC
			LIMIT 1
		',
		[
			'email' => [
				'value' => $attempt[$this->target['table'].'_email'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		if ($last !== false && $id <= $last['auth_'.$this->target['actor'].'_register_attempted_id']) {
			$this->load_error(500, 'Disabled token');
		}
		$this->get_dbh()->beginTransaction();
		$this->pbe(
			'INSERT INTO `'.$this->target['table'].'` SET '.$this->get_set_clause($this->target['columns']),
			$this->get_bind_assocs($this->target['columns'], $this->request['_post'])
		);
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_register_succeeded` SET `auth_'.$this->target['actor'].'_register_attempted_id` = :auth_'.$this->target['actor'].'_register_attempted_id, `'.$this->target['table'].'_id` = :id, `auth_'.$this->target['actor'].'_register_succeeded_microtime` = :microtime', [
			'auth_'.$this->target['actor'].'_register_attempted_id' => [
				'value' => $attempt['auth_'.$this->target['actor'].'_register_attempted_id'],
				'data_type' => PDO::PARAM_STR,
			],
			'id' => [
				'value' => $this->get_dbh()->lastInsertId(),
				'data_type' => PDO::PARAM_INT,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'` SET `'.$this->target['table'].'_id` = :id, `'.$this->target['table'].'_email` = :email, `'.$this->target['table'].'_password` = :password', [
			'id' => [
				'value' => $this->get_dbh()->lastInsertId(),
				'data_type' => PDO::PARAM_INT,
			],
			'email' => [
				'value' => $attempt[$this->target['table'].'_email'],
				'data_type' => PDO::PARAM_INT,
			],
			'password' => [
				'value' => password_hash($this->request['_post']['password'], PASSWORD_DEFAULT),
				'data_type' => PDO::PARAM_STR,
			],
		]);
		$this->get_dbh()->commit();
	}

	public function login()
	{
		$actor = $this->row('SELECT `'.$this->target['table'].'_id` AS `id`, `'.$this->target['table'].'_email` AS `email`, `'.$this->target['table'].'_password` AS `password` FROM `auth_'.$this->target['actor'].'` WHERE `'.$this->target['table'].'_email` = :email', [
			'email' => [
				'value' => $this->request['_post']['email'],
				'data_type' => PDO::PARAM_STR,
			],
		]);
		if ($actor === false) {
			$this->load_error(500, 'Unregistered email');
		}
		if (!password_verify($this->request['_post']['password'], $actor['password'])) {
			$this->load_error(500, 'Incorrect password');
		}
		$_SESSION['auth']['m'] = [
			'id' => $actor['id'],
			'email' => $actor['email'],
		];
	}

	public function change_email_verify($id, $token)
	{
		$attempt = $this->row('SELECT `auth_'.$this->target['actor'].'_change_email_attempted_id`, `'.$this->target['table'].'_id`, `'.$this->target['table'].'_email`, `auth_'.$this->target['actor'].'_change_email_attempted_microtime` AS `microtime` FROM `auth_'.$this->target['actor'].'_change_email_attempted` WHERE `auth_'.$this->target['actor'].'_change_email_attempted_id` = :id AND `auth_'.$this->target['actor'].'_change_email_attempted_token` = :token', [
			'id' => [
				'value' => $id,
				'data_type' => PDO::PARAM_INT,
			],
			'token' => [
				'value' => $token,
				'data_type' => PDO::PARAM_STR,
			],
		]);
		if ($attempt === false) {
			$this->load_error(500, 'Invalid token');
		}
		if ($attempt['microtime'] < $this->request['microtime'] - $this->target['token_expire_minutes'] * 60 * 1000000) {
			$this->load_error(500, 'Expired token');
		}
		$last = $this->row('
			SELECT `auth_'.$this->target['actor'].'_change_email_attempted_id`
			FROM `auth_'.$this->target['actor'].'_change_email_succeeded`
			NATURAL JOIN `auth_'.$this->target['actor'].'_change_email_attempted`
			WHERE `'.$this->target['table'].'_id` = :'.$this->target['table'].'_id
			ORDER BY `auth_'.$this->target['actor'].'_change_email_succeeded_id` DESC
			LIMIT 1
		',
		[
			$this->target['table'].'_id' => [
				'value' => $attempt[$this->target['table'].'_id'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		if ($last !== false && $id <= $last['auth_'.$this->target['actor'].'_change_email_attempted_id']) {
			$this->load_error(500, 'Disabled token');
		}
		$this->get_dbh()->beginTransaction();
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_change_email_succeeded` SET `auth_'.$this->target['actor'].'_change_email_attempted_id` = :auth_'.$this->target['actor'].'_change_email_attempted_id, `auth_'.$this->target['actor'].'_change_email_succeeded_microtime` = :microtime', [
			'auth_'.$this->target['actor'].'_change_email_attempted_id' => [
				'value' => $attempt['auth_'.$this->target['actor'].'_change_email_attempted_id'],
				'data_type' => PDO::PARAM_STR,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->pbe('UPDATE `auth_'.$this->target['actor'].'` SET `'.$this->target['table'].'_email` = :email WHERE `'.$this->target['table'].'_id` = :id', [
			'id' => [
				'value' => $attempt[$this->target['table'].'_id'],
				'data_type' => PDO::PARAM_INT,
			],
			'email' => [
				'value' => $attempt[$this->target['table'].'_email'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->get_dbh()->commit();
	}

	public function forgot_password()
	{
		$actor = $this->row('SELECT `'.$this->target['table'].'_id` AS `id` FROM `auth_'.$this->target['actor'].'` WHERE `'.$this->target['table'].'_email` = :email', [
			'email' => [
				'value' => $this->request['_post']['email'],
				'data_type' => PDO::PARAM_STR,
			],
		]);
		if ($actor === false) {
			$this->load_error(500, 'Unregistered email');
		}
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_reset_password_attempted` SET `'.$this->target['table'].'_id` = :id, `auth_'.$this->target['actor'].'_reset_password_attempted_token` = :token, `auth_'.$this->target['actor'].'_reset_password_attempted_microtime` = :microtime', [
			'id' => [
				'value' => $actor['id'],
				'data_type' => PDO::PARAM_INT,
			],
			'token' => [
				'value' => $this->request['token'],
				'data_type' => PDO::PARAM_STR,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->send_mail('forgot_password', [
			'to' => $this->request['_post']['email'],
			'id' => $this->get_dbh()->lastInsertId(),
			'token' => $this->request['token'],
		]);
	}

	public function reset_password($id, $token)
	{
		$attempt = $this->row('SELECT `auth_'.$this->target['actor'].'_reset_password_attempted_id`, `'.$this->target['table'].'_id`, `auth_'.$this->target['actor'].'_reset_password_attempted_microtime` AS `microtime` FROM `auth_'.$this->target['actor'].'_reset_password_attempted` WHERE `auth_'.$this->target['actor'].'_reset_password_attempted_id` = :id AND `auth_'.$this->target['actor'].'_reset_password_attempted_token` = :token', [
			'id' => [
				'value' => $id,
				'data_type' => PDO::PARAM_INT,
			],
			'token' => [
				'value' => $token,
				'data_type' => PDO::PARAM_STR,
			],
		]);
		if ($attempt === false) {
			$this->load_error(500, 'Invalid token');
		}
		if ($attempt['microtime'] < $this->request['microtime'] - $this->target['token_expire_minutes'] * 60 * 1000000) {
			$this->load_error(500, 'Expired token');
		}
		$last = $this->row('
			SELECT `auth_'.$this->target['actor'].'_reset_password_attempted_id`
			FROM `auth_'.$this->target['actor'].'_reset_password_succeeded`
			NATURAL JOIN `auth_'.$this->target['actor'].'_reset_password_attempted`
			WHERE `'.$this->target['table'].'_id` = :'.$this->target['table'].'_id
			ORDER BY `auth_'.$this->target['actor'].'_reset_password_succeeded_id` DESC
			LIMIT 1
		',
		[
			$this->target['table'].'_id' => [
				'value' => $attempt[$this->target['table'].'_id'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		if ($last !== false && $id <= $last['auth_'.$this->target['actor'].'_reset_password_attempted_id']) {
			$this->load_error(500, 'Disabled token');
		}
		$this->get_dbh()->beginTransaction();
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_reset_password_succeeded` SET `auth_'.$this->target['actor'].'_reset_password_attempted_id` = :auth_'.$this->target['actor'].'_reset_password_attempted_id, `auth_'.$this->target['actor'].'_reset_password_succeeded_microtime` = :microtime', [
			'auth_'.$this->target['actor'].'_reset_password_attempted_id' => [
				'value' => $attempt['auth_'.$this->target['actor'].'_reset_password_attempted_id'],
				'data_type' => PDO::PARAM_STR,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->pbe('UPDATE `auth_'.$this->target['actor'].'` SET `'.$this->target['table'].'_password` = :password WHERE `'.$this->target['table'].'_id` = :id', [
			'id' => [
				'value' => $attempt[$this->target['table'].'_id'],
				'data_type' => PDO::PARAM_INT,
			],
			'password' => [
				'value' => password_hash($this->request['_post']['password'], PASSWORD_DEFAULT),
				'data_type' => PDO::PARAM_STR,
			],
		]);
		$this->get_dbh()->commit();
	}

	public function unregister()
	{
		$actor = $this->row('SELECT `'.$this->target['table'].'_id` AS `id` FROM `auth_'.$this->target['actor'].'` WHERE `'.$this->target['table'].'_id` = :id', [
			'id' => [
				'value' => $_SESSION['auth']['m']['id'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		if ($actor === false) {
			$this->load_error(500, 'Unregistered email');
		}
		$this->get_dbh()->beginTransaction();
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_unregistered` SET `'.$this->target['table'].'_id` = :id, `auth_'.$this->target['actor'].'_unregistered_microtime` = :microtime', [
			'id' => [
				'value' => $actor['id'],
				'data_type' => PDO::PARAM_INT,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->pbe('DELETE FROM `auth_'.$this->target['actor'].'` WHERE `'.$this->target['table'].'_id` = :id', [
			'id' => [
				'value' => $actor['id'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->get_dbh()->commit();
		unset($_SESSION['auth']['m']);
	}

	public function logout()
	{
		unset($_SESSION['auth']['m']);
	}

	public function change_email()
	{
		$this->pbe('INSERT INTO `auth_'.$this->target['actor'].'_change_email_attempted` SET `'.$this->target['table'].'_id` = :id, `'.$this->target['table'].'_email` = :email, `auth_'.$this->target['actor'].'_change_email_attempted_token` = :token, `auth_'.$this->target['actor'].'_change_email_attempted_microtime` = :microtime', [
			'id' => [
				'value' => $_SESSION['auth']['m']['id'],
				'data_type' => PDO::PARAM_INT,
			],
			'email' => [
				'value' => $this->request['_post']['email'],
				'data_type' => PDO::PARAM_STR,
			],
			'token' => [
				'value' => $this->request['token'],
				'data_type' => PDO::PARAM_STR,
			],
			'microtime' => [
				'value' => $this->request['microtime'],
				'data_type' => PDO::PARAM_INT,
			],
		]);
		$this->send_mail('change_email', [
			'to' => $this->request['_post']['email'],
			'id' => $this->get_dbh()->lastInsertId(),
			'token' => $this->request['token'],
		]);
	}

	public function change_password()
	{
		$this->pbe('UPDATE `auth_'.$this->target['actor'].'` SET `'.$this->target['table'].'_password` = :password WHERE `'.$this->target['table'].'_id` = :id', [
			'id' => [
				'value' => $_SESSION['auth']['m']['id'],
				'data_type' => PDO::PARAM_INT,
			],
			'password' => [
				'value' => password_hash($this->request['_post']['password'], PASSWORD_DEFAULT),
				'data_type' => PDO::PARAM_STR,
			],
		]);
		unset($_SESSION['auth']['m']);
	}
}
